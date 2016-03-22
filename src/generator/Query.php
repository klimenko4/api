<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 22.03.16 at 15:46
 */
namespace samsoncms\api\generator;

/**
 * Entity Query class generator.
 *
 * @package samsoncms\api\generator
 */
class Query extends OOP
{
    /**
     * Class uses generation part.
     *
     * @param Metadata $metadata Entity metadata
     */
    protected function createUses(Metadata $metadata)
    {
        $this->generator
            ->newLine('use samsonframework\orm\ArgumentInterface;')
            ->newLine();
    }

    /**
     * Class definition generation part.
     *
     * @param Metadata $metadata Entity metadata
     */
    protected function createDefinition(Metadata $metadata)
    {
        $this->generator
            ->multiComment(array(
                'Class for querying and fetching "' . $metadata->entityRealName . '" instances from database',
                '@method ' . $metadata->entityClassName . ' first();',
                '@method ' . $metadata->entityClassName . '[] find();',
            ))
            ->defClass($metadata->entity . 'Query', '\\'. \samsoncms\api\query\Entity::class);
    }

    /**
     * Class static fields generation part.
     *
     * @param Metadata $metadata Entity metadata
     */
    protected function createStaticFields(Metadata $metadata)
    {
        $this->generator
            ->commentVar('array', 'Collection of real additional field names')
            ->defClassVar('$fieldRealNames', 'public static', $metadata->realNames)
            ->commentVar('array', 'Collection of additional field names')
            ->defClassVar('$fieldNames', 'public static', $metadata->allFieldNames)
            // TODO: two above fields should be protected
            ->commentVar('array', 'Collection of navigation identifiers')
            ->defClassVar('$navigationIDs', 'protected static', array($metadata->entityID))
            ->commentVar('string', 'Entity full class name')
            ->defClassVar('$identifier', 'protected static', $metadata->entityClassName)
            ->commentVar('array', 'Collection of localized additional fields identifiers')
            ->defClassVar('$localizedFieldIDs', 'protected static', $metadata->localizedFieldIDs)
            ->commentVar('array', 'Collection of NOT localized additional fields identifiers')
            ->defClassVar('$notLocalizedFieldIDs', 'protected static', $metadata->notLocalizedFieldIDs)
            ->commentVar('array', 'Collection of localized additional fields identifiers')
            ->defClassVar('$fieldIDs', 'protected static', $metadata->allFieldIDs)
            ->commentVar('array', 'Collection of additional fields value column names')
            ->defClassVar('$fieldValueColumns', 'protected static', $metadata->allFieldValueColumns);
    }

    /**
     * Class methods generation part.
     *
     * @param Metadata $metadata Entity metadata
     */
    protected function createMethods(Metadata $metadata)
    {
        $methods = [];
        // TODO: Add different method generation depending on their field type
        // Generate Query::where() analog for specific field.
        foreach ($metadata->allFieldIDs as $fieldID => $fieldName) {
            $code = "\n\t" . '/**';
            $code .= "\n\t" . ' * Add '.$fieldName.'(#' . $fieldID . ') field query condition.';
            $code .= "\n\t" . ' * @see Generic::where()';
            $code .= "\n\t" . ' * @param ' . $metadata->allFieldTypes[$fieldID] . ' $value Field value';
            $code .= "\n\t" . ' * @param string $relation Field to value condition relation';
            $code .= "\n\t" . ' *';
            $code .= "\n\t" . ' * @return $this Chaining';
            $code .= "\n\t" . ' */';
            $code .= "\n\t" . 'public function ' . $fieldName . '($value, $relation = ArgumentInterface::EQUAL)';
            $code .= "\n\t" . '{';
            $code .= "\n\t\t" . 'return $this->where(\'' . $fieldName . '\', $value, $relation);';
            $code .= "\n\t" . '}';

            $methods[] = $code;
        }

        // Add method text to generator
        $this->generator->text(implode("\n", $methods));
    }
}