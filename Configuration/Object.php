<?php

namespace Jarves\Configuration;

use Jarves\ORM\Builder\Builder;
use Jarves\Tools;

class Object extends Model
{
    protected $attributes = ['id', 'crossRef'];
    protected $_excludeFromExport = ['bundle'];

    /**
     * The id of the object.
     *
     * @var string
     */
    protected $id;

    /**
     * @var Bundle
     */
    protected $bundle;

    /**
     * A label of the object.
     *
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $desc;

    /**
     * @var string
     */
    protected $table;

    /**
     * The class that handles the actual data storage (to a file e.g) of the object interface. Needs 'dataModel'=true.
     *
     * @var string
     */
    protected $class;

    /**
     * The controller php class or service id to use as controller. (Used between Jarves\Objects and your $class/ORM-class)
     *
     * @var string
     */
    protected $controller;

    /**
     * Which field (the value of it) shall be used as default label for the object.
     *
     * @var string
     */
    protected $labelField;

    /**
     * @var string
     */
    protected $labelTemplate;

    /**
     * Comma separated list of fields which are selected per default.
     *
     * @var string
     */
    protected $defaultSelection;

    /**
     * Comma separated list of fields which are blacklisted and therefore not selectable
     * through the object API.
     *
     * @var string
     */
    protected $blacklistSelection;

    /**
     * @var \Jarves\Admin\FieldTypes\RelationDefinitionInterface[]
     */
    private $relations;

    /**
     * @var array
     */
    private $indexes;

    /**
     * The data model in the back.
     *
     * @var string
     */
    protected $dataModel = 'propel';

    /**
     * Whether we handle multi languages or not.
     *
     * @var bool
     */
    protected $multiLanguage = false;

    /**
     * Whether we handle workspaces or not.
     *
     * @var bool
     */
    protected $workspace = false;

    /**
     * @var bool
     */
    protected $domainDepended = false;

    /**
     * Which field shall be used as default label for a default text input `field` instance in the user interface for example.
     * (jarves.Field instance)
     *
     * @var string
     */
    protected $singleItemLabelField;

    /**
     * Extra fields to select for single item.
     *
     * @var string|array
     */
    protected $singleItemSelection;

    /**
     * @var string
     */
    protected $fieldTemplate;

    /**
     * If the object is nested.
     *
     * @var bool
     */
    protected $nested = false;

    /**
     * @var bool
     */
    protected $nestedRootAsObject = false;

    /**
     * @var string
     */
    protected $nestedRootObject;

    /**
     * @var string
     */
    protected $nestedRootObjectField;

    /**
     * @var string
     */
    protected $nestedRootObjectLabelField;

    /**
     * @var string
     */
    protected $nestedRootObjectExtraFields;

    /**
     * Which field shall be used as default label for the nested set object.
     *
     * @var string
     */
    protected $treeLabel;

    /**
     * @var string
     */
    protected $treeTemplate;

    /**
     * Which fields are selected per default in the nested rest.
     *
     * @var string
     */
    protected $treeFields;

    /**
     * @var string
     */
    protected $treeIcon;

    /**
     * @var TreeIconMapping
     */
    protected $treeIconMapping;

    /**
     * Javascript class/function for the tree user interface.
     *
     * @var string
     */
    protected $treeInterfaceClass;

    /**
     * @var string default|custom
     */
    protected $treeInterface = 'default';

    /**
     * @var string
     */
    protected $treeDefaultIcon;

    /**
     * @var bool
     */
    protected $treeFixedIcon = false;

    /**
     * @var string
     */
    protected $treeRootObjectIconPath;

    /**
     * @var bool
     */
    protected $treeRootObjectFixedIcon = false;

    /**
     * Which field shall be used as label for the nested root.
     *
     * @var string
     */
    protected $treeRootFieldLabel;

    /**
     * @var string
     */
    protected $treeRootFieldTemplate;

    /**
     * Comma separated list of field which shall be selected per default in the rest api.
     *
     * @var string
     */
    protected $treeRootFieldFields;

    /**
     * The javascript class/function to be used as the user interface for object browsing/listing.
     *
     * @var
     */
    protected $browserInterfaceClass;

    /**
     * @var string default|custom
     */
    protected $browserInterface = 'default';

    /**
     * @var string custom|default
     */
    protected $browserDataModel = 'default';

    /**
     * The PHP class which handles the retrieving of items for the browsing rest api.
     *
     * @var string
     */
    protected $browserDataModelClass;

    /**
     * @var Field[]
     */
    protected $browserOptions;

    /**
     * @var array
     */
    protected $limitDataSets;

    /**
     * The path of the entry point that acts as a listing window.
     *
     * @var string|null
     */
    protected $listEntryPoint;

    /**
     * The path of the entry point that acts as a edit window.
     *
     * @var string|null
     */
    protected $editEntryPoint;

    /**
     * The path of the entry point that acts as a add window.
     *
     * @var string|null
     */
    protected $addEntryPoint;

    /**
     * @var Field[]
     */
    protected $fields;

    /**
     * @var Field[]
     */
    protected $browserColumns;

    /**
     * The callable string pointing to a method/function that generates the actual public url for a object pk.
     *
     * example:
     *
     *  `\MyBundle\ControllerXy::getPublicUrl`
     *
     * The function should have this signature:
     *
     *   `function($objectKey, array $pk, array $pluginProperties )
     *
     * @var string
     */
    protected $publicUrlGenerator;

//    /**
//     * @var array
//     */
//    private $virtualFields;

    /**
     * @var array
     */
    private $primaryKeys;

    /**
     * @var bool
     */
    protected $excludeFromREST = false;

    /**
     * @var bool
     */
    protected $crossRef = false;

    /**
     * Do whatever is needed to setup the runtime environment correctly.
     *
     * e.g. create cross foreignKeys for 1-to-n relations.
     *
     * @param Configs $configs
     */
    public function bootRunTime(Configs $configs)
    {
        if ($this->getFields()) {
            foreach ($this->getFields() as $field) {
                $field->bootRunTime($this, $configs);
            }
        }
    }

//    /**
//     * Do whatever is needed to setup the build environment correctly.
//     *
//     * e.g. create crossTables for n-to-n relations.
//     *
//     * @param Configs $configs
//     */
//    public function bootBuildTime(Configs $configs)
//    {
//        if ($this->getFields()) {
//            foreach ($this->getFields() as $field) {
//                $field->bootBuildTime($this, $configs);
//            }
//        }
//    }

    /**
     * @return array
     */
    public function __sleep()
    {
        $vars = parent::__sleep();
        $vars[] = 'relations';
        $vars[] = 'indexes';
        return $vars;
    }

    /**
     * @param boolean $excludeFromREST
     */
    public function setExcludeFromREST($excludeFromREST)
    {
        $this->excludeFromREST = $excludeFromREST;
    }

    /**
     * @return boolean
     */
    public function getExcludeFromREST()
    {
        return $this->excludeFromREST;
    }

    /**
     * @param array $indexes
     */
    public function setIndexes($indexes)
    {
        $this->indexes = $indexes;
    }

    /**
     * @return array
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @param \Jarves\Admin\FieldTypes\RelationDefinitionInterface[] $relations
     */
    public function setRelations(array $relations)
    {
        $this->relations = [];
        foreach ($relations as $relation) {
            $this->relations[strtolower($relation->getName())] = $relation;
        }
    }

    /**
     * @param \Jarves\Admin\FieldTypes\RelationDefinitionInterface $relation
     */
    public function addRelation($relation)
    {
        $this->relations[strtolower($relation->getName())] = $relation;
    }

    /**
     * @return \Jarves\Admin\FieldTypes\RelationDefinitionInterface[]
     */
    public function getRelations()
    {
        return $this->relations ?: [];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasRelation($name)
    {
        return isset($this->relations[strtolower($name)]);
    }

    /**
     * @param $name
     * @return \Jarves\Admin\FieldTypes\RelationDefinitionInterface
     */
    public function getRelation($name)
    {
        return @$this->relations[strtolower($name)];
    }

//    /**
//     * @param Field[] $virtualFields
//     */
//    public function setVirtualFields(array $virtualFields = null)
//    {
//        $this->virtualFields = $virtualFields;
//    }
//
//    /**
//     * @param Field $virtualField
//     */
//    public function addVirtualField(Field $virtualField)
//    {
//        if (!in_array($virtualField, $this->virtualFields ? : [], true)) {
//            $this->virtualFields[] = $virtualField;
//        }
//    }
//
//    /**
//     * @return Field[]
//     */
//    public function getVirtualFields()
//    {
//        return $this->virtualFields;
//    }


    /**
     * @param string $blacklistSelection
     */
    public function setBlacklistSelection($blacklistSelection)
    {
        $this->blacklistSelection = $blacklistSelection;
    }

    /**
     * @return string
     */
    public function getBlacklistSelection()
    {
        return $this->blacklistSelection;
    }

    /**
     * @param string $browserDataModel
     */
    public function setBrowserDataModel($browserDataModel)
    {
        $this->browserDataModel = $browserDataModel;
    }

    /**
     * @return string
     */
    public function getBrowserDataModel()
    {
        return $this->browserDataModel;
    }

    /**
     * @param string $browserDataModelClass
     */
    public function setBrowserDataModelClass($browserDataModelClass)
    {
        $this->browserDataModelClass = $browserDataModelClass;
    }

    /**
     * @return string
     */
    public function getBrowserDataModelClass()
    {
        return $this->browserDataModelClass;
    }

    /**
     * @param string $browserInterface
     */
    public function setBrowserInterface($browserInterface)
    {
        $this->browserInterface = $browserInterface;
    }

    /**
     * @return string
     */
    public function getBrowserInterface()
    {
        return $this->browserInterface;
    }

    /**
     * @param  $browserInterfaceClass
     */
    public function setBrowserInterfaceClass($browserInterfaceClass)
    {
        $this->browserInterfaceClass = $browserInterfaceClass;
    }

    /**
     * @return
     */
    public function getBrowserInterfaceClass()
    {
        return $this->browserInterfaceClass;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $dataModel
     */
    public function setDataModel($dataModel)
    {
        $this->dataModel = $dataModel;
    }

    /**
     * @return string
     */
    public function getDataModel()
    {
        return $this->dataModel;
    }

    /**
     * @param string $defaultSelection
     */
    public function setDefaultSelection($defaultSelection)
    {
        $this->defaultSelection = $defaultSelection;
    }

    /**
     * @return string
     */
    public function getDefaultSelection()
    {
        return $this->defaultSelection;
    }

    /**
     * @param boolean $domainDepended
     */
    public function setDomainDepended($domainDepended)
    {
        $this->domainDepended = $this->bool($domainDepended);
    }

    /**
     * @return boolean
     */
    public function getDomainDepended()
    {
        return $this->domainDepended;
    }

    /**
     * @param string $fieldLabel
     */
    public function setSingleItemLabelField($fieldLabel)
    {
        $this->singleItemLabelField = $fieldLabel;
    }

    /**
     * @return string
     */
    public function getSingleItemLabelField()
    {
        return $this->singleItemLabelField;
    }

    /**
     * @return array|string
     */
    public function getSingleItemSelection()
    {
        return $this->singleItemSelection;
    }

    /**
     * @param array|string $fieldFields
     */
    public function setSingleItemSelection($fieldFields)
    {
        $this->singleItemSelection = $fieldFields;
    }

    /**
     * @param string $fieldTemplate
     */
    public function setFieldTemplate($fieldTemplate)
    {
        $this->fieldTemplate = $fieldTemplate;
    }

    /**
     * @return string
     */
    public function getFieldTemplate()
    {
        return $this->fieldTemplate;
    }

    /**
     * @param Field[] $fields
     */
    public function setFields(array $fields = array())
    {
        $this->fields = [];
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    /**
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $field->setObjectDefinition($this);
        $this->fields[$field->getColumnName()] = $field;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasField($name)
    {
        return !!$this->getField($name);
    }

    /**
     * @return Field[] underscored fieldNames as index
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array camelCased fieldNames as index
     */
    public function getFieldsArray()
    {
        $fields = array();
        if (null !== $this->fields) {
            foreach ($this->fields as $field) {
                $fields[lcfirst($field->getId())] = $field->toArray();
            }
        }

        return $fields;
    }

    /**
     * @param $fieldId
     *
     * @return Field
     */
    public function getField($fieldId)
    {
        if (null !== $this->fields) {
            $id = Tools::camelcase2Underscore($fieldId);

            return isset($this->fields[$id]) ? $this->fields[$id] : null;
        }
    }

    /**
     * @param Field[] $fields
     */
    public function setBrowserColumns(array $fields = null)
    {
        $this->browserColumns = $fields;
        if ($this->browserColumns) {
            foreach ($this->browserColumns as $field) {
                $field->setObjectDefinition($this);
            }
        }
    }

    /**
     * @return Field[]
     */
    public function getBrowserColumns()
    {
        return $this->browserColumns;
    }

    /**
     * @return array
     */
    public function getBrowserColumnsArray()
    {
        $fields = array();
        if (null !== $this->browserColumns) {
            foreach ($this->browserColumns as $field) {
                $field->setObjectDefinition($this);
                $fields[lcfirst($field->getId())] = $field->toArray();
            }
        }

        return $fields;
    }

    /**
     * @return Field[]
     */
    public function getPrimaryKeys()
    {
        if (null === $this->primaryKeys) {
            $this->primaryKeys = array();
            foreach ($this->getFields() as $field) {
                if ($field->isPrimaryKey()) {
                    $this->primaryKeys[] = $field;
                }
            }
        }

        return $this->primaryKeys;
    }

    /**
     * @return string[]
     */
    public function getPrimaryKeyNames()
    {
        $names = array();
        foreach ($this->getPrimaryKeys() as $field) {
            if ($field->isPrimaryKey()) {
                $names[] = lcfirst($field->getId());
            }
        }
        return $names;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the object id. E.g. Node
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the full object key. E.g. jarves/node
     *
     * @return string
     */
    public function getKey()
    {
        return ($this->getBundle() ? $this->getBundle()->getName() : '[no-bundle]') . '/'.lcfirst($this->getId());
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $labelField
     */
    public function setLabelField($labelField)
    {
        $this->labelField = $labelField;
    }

    /**
     * Which field (the value of it) shall be used as default label for the object.
     *
     * @return string
     */
    public function getLabelField()
    {
        return $this->labelField;
    }

    /**
     * @param string $labelTemplate
     */
    public function setLabelTemplate($labelTemplate)
    {
        $this->labelTemplate = $labelTemplate;
    }

    /**
     * @return string
     */
    public function getLabelTemplate()
    {
        return $this->labelTemplate;
    }

    /**
     * @param boolean $multiLanguage
     */
    public function setMultiLanguage($multiLanguage)
    {
        $this->multiLanguage = $this->bool($multiLanguage);
    }

    /**
     * @return boolean
     */
    public function getMultiLanguage()
    {
        return $this->multiLanguage;
    }

    /**
     * @param boolean $nested
     */
    public function setNested($nested)
    {
        $this->nested = $this->bool($nested);
    }

    /**
     * @return boolean
     */
    public function getNested()
    {
        return $this->nested;
    }

    public function isNested()
    {
        return true === $this->nested;
    }

    /**
     * @param boolean $nestedRootAsObject
     */
    public function setNestedRootAsObject($nestedRootAsObject)
    {
        $this->nestedRootAsObject = $this->bool($nestedRootAsObject);
    }

    /**
     * @return boolean
     */
    public function getNestedRootAsObject()
    {
        return $this->nestedRootAsObject;
    }

    /**
     * @param string $nestedRootObject
     */
    public function setNestedRootObject($nestedRootObject)
    {
        $this->nestedRootObject = $nestedRootObject;
    }

    /**
     * @return string
     */
    public function getNestedRootObject()
    {
        return $this->nestedRootObject;
    }

    /**
     * @param string $nestedRootObjectExtraFields
     */
    public function setNestedRootObjectExtraFields($nestedRootObjectExtraFields)
    {
        $this->nestedRootObjectExtraFields = $nestedRootObjectExtraFields;
    }

    /**
     * @return string
     */
    public function getNestedRootObjectExtraFields()
    {
        return $this->nestedRootObjectExtraFields;
    }

    /**
     * @param string $nestedRootObjectField
     */
    public function setNestedRootObjectField($nestedRootObjectField)
    {
        $this->nestedRootObjectField = $nestedRootObjectField;
    }

    /**
     * @return string
     */
    public function getNestedRootObjectField()
    {
        return $this->nestedRootObjectField;
    }

    /**
     * @param string $nestedRootObjectLabelField
     */
    public function setNestedRootObjectLabelField($nestedRootObjectLabelField)
    {
        $this->nestedRootObjectLabelField = $nestedRootObjectLabelField;
    }

    /**
     * @return string
     */
    public function getNestedRootObjectLabelField()
    {
        return $this->nestedRootObjectLabelField;
    }

    /**
     * @param string $treeDefaultIcon
     */
    public function setTreeDefaultIcon($treeDefaultIcon)
    {
        $this->treeDefaultIcon = $treeDefaultIcon;
    }

    /**
     * @return string
     */
    public function getTreeDefaultIcon()
    {
        return $this->treeDefaultIcon;
    }

    /**
     * @param string $treeFields
     */
    public function setTreeFields($treeFields)
    {
        $this->treeFields = $treeFields;
    }

    /**
     * @return string
     */
    public function getTreeFields()
    {
        return $this->treeFields;
    }

    /**
     * @param boolean $treeFixedIcon
     */
    public function setTreeFixedIcon($treeFixedIcon)
    {
        $this->treeFixedIcon = $this->bool($treeFixedIcon);
    }

    /**
     * @return boolean
     */
    public function getTreeFixedIcon()
    {
        return $this->treeFixedIcon;
    }

    /**
     * @param string $treeIcon
     */
    public function setTreeIcon($treeIcon)
    {
        $this->treeIcon = $treeIcon;
    }

    /**
     * @return string
     */
    public function getTreeIcon()
    {
        return $this->treeIcon;
    }

    /**
     * @param TreeIconMapping $treeIconMapping
     */
    public function setTreeIconMapping(TreeIconMapping $treeIconMapping = null)
    {
        $this->treeIconMapping = $treeIconMapping;
    }

    /**
     * @return TreeIconMapping
     */
    public function getTreeIconMapping()
    {
        return $this->treeIconMapping;
    }

    /**
     * @param string $treeInterface
     */
    public function setTreeInterface($treeInterface)
    {
        $this->treeInterface = $treeInterface;
    }

    /**
     * @return string
     */
    public function getTreeInterface()
    {
        return $this->treeInterface;
    }

    /**
     * @param string $treeInterfaceClass
     */
    public function setTreeInterfaceClass($treeInterfaceClass)
    {
        $this->treeInterfaceClass = $treeInterfaceClass;
    }

    /**
     * @return string
     */
    public function getTreeInterfaceClass()
    {
        return $this->treeInterfaceClass;
    }

    /**
     * @param string $treeLabel
     */
    public function setTreeLabel($treeLabel)
    {
        $this->treeLabel = $treeLabel;
    }

    /**
     * @return string
     */
    public function getTreeLabel()
    {
        return $this->treeLabel;
    }

    /**
     * @param string $treeRootFieldFields
     */
    public function setTreeRootFieldFields($treeRootFieldFields)
    {
        $this->treeRootFieldFields = $treeRootFieldFields;
    }

    /**
     * @return string
     */
    public function getTreeRootFieldFields()
    {
        return $this->treeRootFieldFields;
    }

    /**
     * @param string $treeRootFieldLabel
     */
    public function setTreeRootFieldLabel($treeRootFieldLabel)
    {
        $this->treeRootFieldLabel = $treeRootFieldLabel;
    }

    /**
     * @return string
     */
    public function getTreeRootFieldLabel()
    {
        return $this->treeRootFieldLabel;
    }

    /**
     * @param string $treeRootFieldTemplate
     */
    public function setTreeRootFieldTemplate($treeRootFieldTemplate)
    {
        $this->treeRootFieldTemplate = $treeRootFieldTemplate;
    }

    /**
     * @return string
     */
    public function getTreeRootFieldTemplate()
    {
        return $this->treeRootFieldTemplate;
    }

    /**
     * @param boolean $treeRootObjectFixedIcon
     */
    public function setTreeRootObjectFixedIcon($treeRootObjectFixedIcon)
    {
        $this->treeRootObjectFixedIcon = $this->bool($treeRootObjectFixedIcon);
    }

    /**
     * @return boolean
     */
    public function getTreeRootObjectFixedIcon()
    {
        return $this->treeRootObjectFixedIcon;
    }

    /**
     * @param string $treeRootObjectIconPath
     */
    public function setTreeRootObjectIconPath($treeRootObjectIconPath)
    {
        $this->treeRootObjectIconPath = $treeRootObjectIconPath;
    }

    /**
     * @return string
     */
    public function getTreeRootObjectIconPath()
    {
        return $this->treeRootObjectIconPath;
    }

    /**
     * @param string $treeTemplate
     */
    public function setTreeTemplate($treeTemplate)
    {
        $this->treeTemplate = $treeTemplate;
    }

    /**
     * @return string
     */
    public function getTreeTemplate()
    {
        return $this->treeTemplate;
    }

    /**
     * @param boolean $workspace
     */
    public function setWorkspace($workspace)
    {
        $this->workspace = filter_var($workspace, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return boolean
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param Field[] $browserOptions
     */
    public function setBrowserOptions(array $browserOptions = null)
    {
        $this->browserOptions = $browserOptions;
    }

    /**
     * @return Field[]
     */
    public function getBrowserOptions()
    {
        return $this->browserOptions;
    }

    /**
     * @param \Jarves\Configuration\Condition $limitDataSets
     */
    public function setLimitDataSets($limitDataSets)
    {
        //todo, read from xml
        $this->limitDataSets = $limitDataSets;
    }

    /**
     * @return \Jarves\Configuration\Condition
     */
    public function getLimitDataSets()
    {
        return $this->limitDataSets;
    }

    /**
     * @param string $publicUrlGenerator
     */
    public function setPublicUrlGenerator($publicUrlGenerator)
    {
        $this->publicUrlGenerator = $publicUrlGenerator;
    }

    /**
     * @return string
     */
    public function getPublicUrlGenerator()
    {
        return $this->publicUrlGenerator;
    }

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param Bundle $bundle
     */
    public function setBundle(Bundle $bundle = null)
    {
        $this->bundle = $bundle;
    }

    /**
     * @return Bundle
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param boolean $crossRef
     */
    public function setCrossRef($crossRef)
    {
        $this->crossRef = $crossRef;
    }

    /**
     * @return boolean
     */
    public function getCrossRef()
    {
        return $this->crossRef;
    }

    /**
     * @return bool
     */
    public function isCrossRef()
    {
        return !!$this->crossRef;
    }

    /**
     * @param null|string $addEntryPoint
     */
    public function setAddEntryPoint($addEntryPoint)
    {
        $this->addEntryPoint = $addEntryPoint;
    }

    /**
     * @return null|string
     */
    public function getAddEntryPoint()
    {
        return $this->addEntryPoint;
    }

    /**
     * @param null|string $editEntryPoint
     */
    public function setEditEntryPoint($editEntryPoint)
    {
        $this->editEntryPoint = $editEntryPoint;
    }

    /**
     * @return null|string
     */
    public function getEditEntryPoint()
    {
        return $this->editEntryPoint;
    }

    /**
     * @param null|string $listEntryPoint
     */
    public function setListEntryPoint($listEntryPoint)
    {
        $this->listEntryPoint = $listEntryPoint;
    }

    /**
     * @return null|string
     */
    public function getListEntryPoint()
    {
        return $this->listEntryPoint;
    }
}