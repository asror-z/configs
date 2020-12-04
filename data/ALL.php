<?php

/**
 *
 *
 * Author:  Asror Zakirov
 * https://www.linkedin.com/in/asror-zakirov
 * https://www.facebook.com/asror.zakirov
 * https://github.com/asror-z
 *
 */

use zetsoft\system\Az;


/**
 *
 * Vars
 */


$coreIP = $boot->ipDb();


/**
 *
 * Constants
 */

$classIgnore = [
    'CoreMain',
];

const cacheIgnore = [
];

$cacheIgnoreMain = [

];


const cacheConfig = [
    'all' => true,
    'one' => true,
    'scalar' => true,
    'count' => true,
    'sum' => true,
    'average' => true,
    'min' => true,
    'max' => true,
    'exists' => false,
];


/**
 *
 * Validator
 */


const validatorBoolean = 'boolean';
const validatorCaptcha = 'captcha';
const validatorCompare = 'compare';
const validatorDate = 'date';
const validatorDatetime = 'datetime';
const validatorTime = 'time';
const validatorDefault = 'default';
const validatorDouble = 'double';
const validatorEach = 'each';
const validatorEmail = 'email';
const validatorExist = 'exist';
const validatorFile = 'file';
const validatorFilter = 'filter';
const validatorImage = 'image';
const validatorIn = 'in';
const validatorInteger = 'integer';
const validatorMatch = 'match';
const validatorRequired = 'required';
const validatorSafe = 'safe';
const validatorString = 'string';
const validatorTrim = 'trim';
const validatorUnique = 'unique';
const validatorUrl = 'url';
const validatorIp = 'ip';

const validator = [
    validatorBoolean => 'validatorBoolean',
    validatorCaptcha => 'validatorCaptcha',
    validatorCompare => 'validatorCompare',
    validatorDate => 'validatorDate',
    validatorDatetime => 'validatorDatetime',
    validatorTime => 'validatorTime',
    validatorDefault => 'validatorDefault',
    validatorDouble => 'validatorDouble',
    validatorEach => 'validatorEach',
    validatorEmail => 'validatorEmail',
    validatorExist => 'validatorExist',
    validatorFile => 'validatorFile',
    validatorFilter => 'validatorFilter',
    validatorImage => 'validatorImage',
    validatorIn => 'validatorIn',
    validatorInteger => 'validatorInteger',
    validatorMatch => 'validatorMatch',
    validatorRequired => 'validatorRequired',
    validatorSafe => 'validatorSafe',
    validatorString => 'validatorString',
    validatorTrim => 'validatorTrim',
    validatorUnique => 'validatorUnique',
    validatorUrl => 'validatorUrl',
    validatorIp => 'validatorIp',
];


/**
 *
 * Db types
 */

const dbTypeTimestamp = 'timestamp';
const dbTypeJsonb = 'jsonb';
const dbTypeChar = 'char';
const dbTypeString = 'string';
const dbTypeText = 'text';
const dbTypeTinyInteger = 'tinyInteger';
const dbTypeSmallInteger = 'smallInteger';
const dbTypeInteger = 'integer';
const dbTypeBigInteger = 'bigInteger';
const dbTypeFloat = 'float';
const dbTypeDouble = 'double';
const dbTypeDecimal = 'decimal';
const dbTypeDateTime = 'dateTime';
const dbTypeTime = 'time';
const dbTypeDate = 'date';
const dbTypeBinary = 'binary';
const dbTypeBoolean = 'boolean';
const dbTypeMoney = 'money';
const dbTypeJson = 'json';

const dbType = [
    dbTypeTimestamp => 'dbTypeTimestamp',
    dbTypeJsonb => 'dbTypeJsonb',
    dbTypeJson => 'dbTypeJson',
    dbTypeChar => 'dbTypeChar',
    dbTypeString => 'dbTypeString',
    dbTypeText => 'dbTypeText',
    dbTypeTinyInteger => 'dbTypeTinyInteger',
    dbTypeSmallInteger => 'dbTypeSmallInteger',
    dbTypeInteger => 'dbTypeInteger',
    dbTypeBigInteger => 'dbTypeBigInteger',
    dbTypeFloat => 'dbTypeFloat',
    dbTypeDouble => 'dbTypeDouble',
    dbTypeDecimal => 'dbTypeDecimal',
    dbTypeDateTime => 'dbTypeDateTime',
    dbTypeTime => 'dbTypeTime',
    dbTypeDate => 'dbTypeDate',
    dbTypeBinary => 'dbTypeBinary',
    dbTypeBoolean => 'dbTypeBoolean',
    dbTypeMoney => 'dbTypeMoney',
];

const dbTypeCast = [
    'timestamp' => dbTypeTimestamp,
    'jsonb' => dbTypeJsonb,
    'char' => dbTypeChar,
    'string' => dbTypeString,
    'text' => dbTypeText,
    'tinyint' => dbTypeTinyInteger,
    'smallint' => dbTypeSmallInteger,
    'integer' => dbTypeInteger,
    'bigint' => dbTypeBigInteger,
    'float' => dbTypeFloat,
    'double' => dbTypeDouble,
    'decimal' => dbTypeDecimal,
    'datetime' => dbTypeDateTime,
    'time' => dbTypeTime,
    'date' => dbTypeDate,
    'binary' => dbTypeBinary,
    'boolean' => dbTypeBoolean,
    'money' => dbTypeMoney,
    'json' => dbTypeJson,
];

const excludedColumn = [
    'created_at' => Azl . 'Время создания',
    'modified_at' => Azl . 'Время изменения',
    'created_by' => Azl . 'Создан пользователем',
    'modified_by' => Azl . 'Изменен пользователем',
];

const softColumn = [
    'deleted_at' => Azl . 'Время удаления',
    'deleted_by' => Azl . 'Удалён пользователем',
    'deleted_text' => Azl . 'Комментария удаления'
];

const systemColumn = [
    'id',
    'name',
    'created_at',
    'created_by',
    'modified_at',
    'modified_by',
];

const extraColumn = [
    'id' => 'ID',
    'sort' => 'Sort',

    'name' => 'Name',
    'code' => 'Code',
    'guid' => 'Guid',
    'title' => 'Title',

    'tranz' => 'Tranz',
    'check' => 'Check',
    'accept' => 'Accept',
    'active' => 'Active',
];

const columnAuto = [
    'name' => 'name',
    'code' => 'code',
    'guid' => 'guid',
];

const trashColumn = [
    'id',
    'name',
    'deleted_at',
    'deleted_by',
    'deleted_text',
];


/**
 *
 * Dynamic Params
 */
const paramIsUpdate = 'isUpdate';
const paramSecondSave = 'secondSave';
const paramNoWarning = 'noWarning';

/**
 *
 * Static Params
 */

const paramTransact = 'paramTransact';
const paramMigration = 'paramMigration';
const paramFull = 'paramFull';
const paramInsertCreate = 'paramInsertCreate';
const paramInsertApply = 'paramInsertApply';
const paramPuter = 'paramPuter';
const paramNorms = 'paramNorms';
const paramModel = 'paramModel';

const paramAction = 'action';

const paramError = 'paramError';
const paramNoEvent = 'paramNoEvent';
const paramIframe = 'paramIframe';
const paramMethod = 'paramMethod';
const paramRest = 'paramRest';
const paramMethodMini = 'methodMini';
const paramRequired = 'paramRequired';
const paramRelatedOn = 'paramRelatedOn';
const paramModelIsDeleted = 'paramModelIsDeleted';

const paramChangeReloadId = 'paramChangeReloadId';
const paramChangeSubmitId = 'paramChangeSubmitId';


const eventChange = 'change';
const eventSelect = 'select';
const eventChangePasteKeyup = 'change paste keyup';
const eventChangePasteKeyupSelect = 'change paste keyup select';
const eventChangePasteKeyupSelectCut = 'change paste keyup cut select';
const eventChangeSwitch = 'change switchChange.bootstrapSwitch isComplete filebatchuploadsuccess';
const eventInputPropertychange = 'input propertychange';

        

const eventChangeALL = 'change paste keyup cut select';


const sessionFormConfigs = 'sessionFormConfigs';







