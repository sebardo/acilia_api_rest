<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ValidatorManager
 * @package App\Service
 *
 * Service used to validate requests
 */
class ValidatorManager
{
    private $manager;

    private $validator;

    protected $propertyInfo;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function getPropertyInfo($manager)
    {
        $reflectionExtractor = new ReflectionExtractor();
        $doctrineExtractor = new DoctrineExtractor($manager);
        return new PropertyInfoExtractor([$reflectionExtractor, $doctrineExtractor],[$doctrineExtractor, $reflectionExtractor]);
    }

    public function getSchema($entity)
    {
        $propertyInfo = $this->getPropertyInfo($this->manager);
        //get property service
        $properties = $propertyInfo->getProperties(get_class($entity));

        $validatorSchema = [];
        foreach ($properties as $property){
            $prop = $propertyInfo->getTypes(get_class($entity), $property);
            if(isset($prop[0])) $validatorSchema[$property] = $prop[0];
        }

        return $validatorSchema;
    }

    public function populateEntity($request, $entity, $validatorSchema)
    {
        //get request data
        $data = json_decode($request->getContent(), true);

        //populate entity with parameters
        foreach ($data as $key => $value){
            $setter = 'set'.ucfirst($key);
            if(method_exists($entity, $setter)){
                if($validatorSchema[$key]->getBuiltinType() == 'object' && str_starts_with ($validatorSchema[$key]->getClassName(), 'App\Entity')){
                    $entityRelationClass = $this->manager->getRepository($validatorSchema[$key]->getClassName())->find($value);
                    if(get_class($entityRelationClass) == $validatorSchema[$key]->getClassName()){
                        $item = $entityRelationClass;
                    }
                }else{
                    $item = (empty($value)) ? null : $value;
                }
                $entity->$setter($item);
            }
        }
    }

    /**
     * Validation
     */
    public function validate(Request $request, $entity, $groups=[])
    {
        //generate schema from entity class
        $validatorSchema = $this->getSchema($entity);

        try {
            //build entity with request information
            $this->populateEntity($request, $entity, $validatorSchema);

            //validate entity
            $violationList = $this->validator->validate($entity, null, $groups);
            $returnErrors = [];
            if(count($violationList) > 0 ){
                foreach ($violationList as $item){
                    if($item instanceof ConstraintViolation){
                        $returnErrors[$item->getPropertyPath()] = $item->getMessage();
                    }
                }
                return new JsonResponse(['status' => 'error', 'message' => $returnErrors], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $e){
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $entity;
    }

}
