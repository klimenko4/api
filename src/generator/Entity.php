<?php
//[PHPCOMPRESSOR(remove,start)]
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 22.03.16 at 15:46
 */
namespace samsoncms\api\generator;

/**
 * Entity class generator.
 *
 * @package samsoncms\api\generator
 */
class Entity extends Generic
{
    /**
     * Class definition generation part.
     *
     * @param \samsoncms\api\generator\metadata\Generic $metadata Entity metadata
     */
    protected function createDefinition($metadata)
    {
        /**
         * TODO: Parent problem
         * Should be changed to merging fields instead of extending with OOP for structure_relation support
         * or creating traits and using them on shared parent entities.
         */
        $parentClass = null !== $metadata->parent
            ? $metadata->parent->entityClassName
            : '\\'.\samsoncms\api\Entity::class;

        $this->generator
            ->multiComment(array('"' . $metadata->entityRealName . '" database entity class'))
            ->defClass($metadata->entity, $parentClass);

        return $metadata->entity;
    }

    /**
     * Class constants generation part.
     *
     * @param \samsoncms\api\generator\metadata\Generic $metadata Entity metadata
     */
    protected function createConstants($metadata)
    {
        $this->generator
            ->commentVar('string', 'Entity full class name, use ::class instead')
            ->defClassConst('ENTITY', $metadata->entityClassName)
            ->commentVar('string', 'Entity manager full class name')
            ->defClassConst('MANAGER', $metadata->entityClassName . 'Query')
            ->commentVar('string', 'Entity database identifier')
            ->defClassConst('IDENTIFIER', $metadata->entityID)
            ->commentVar('string', 'Not transliterated entity name')
            ->defClassConst('NAME', $metadata->entityRealName);

        // Create all entity fields constants storing each additional field metadata
        foreach ($metadata->allFieldIDs as $fieldID => $fieldName) {
            $this->generator
                ->commentVar('string', $metadata->fieldDescriptions[$fieldID] . ' variable name')
                ->defClassConst('F_' . $fieldName, $fieldName)
                ->commentVar('string', $metadata->fieldDescriptions[$fieldID] . ' additional field identifier')
                ->defClassConst('F_' . $fieldName . '_ID', $fieldID);
        }
    }

    /**
     * Class fields generation part.
     *
     * @param \samsoncms\api\generator\metadata\Generic $metadata Entity metadata
     */
    protected function createFields($metadata)
    {
        foreach ($metadata->allFieldIDs as $fieldID => $fieldName) {
            $this->generator
                ->commentVar($metadata->allFieldTypes[$fieldID], $metadata->fieldDescriptions[$fieldID])
                ->defClassVar('$' . $fieldName, 'public');
        }
    }

    /**
     * Class static fields generation part.
     *
     * @param \samsoncms\api\generator\metadata\Generic $metadata Entity metadata
     */
    protected function createStaticFields($metadata)
    {
        return $this->generator
            ->commentVar('array', 'Collection of navigation identifiers')
            ->defClassVar('$navigationIDs', 'protected static', array($metadata->entityID))
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$_sql_select', 'public static ', $metadata->arSelect)
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$_attributes', 'public static ', $metadata->arAttributes)
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$_map', 'public static ', $metadata->arMap)
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$_sql_from', 'public static ', $metadata->arFrom)
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$_own_group', 'public static ', $metadata->arGroup)
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$_relation_alias', 'public static ', $metadata->arRelationAlias)
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$_relation_type', 'public static ', $metadata->arRelationType)
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$_relations', 'public static ', $metadata->arRelations)
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$fieldIDs', 'protected static ', $metadata->allFieldIDs)
            ->commentVar('array', '@deprecated Old ActiveRecord data')
            ->defClassVar('$fieldValueColumns', 'protected static ', $metadata->allFieldValueColumns);
    }
}
//[PHPCOMPRESSOR(remove,end)]