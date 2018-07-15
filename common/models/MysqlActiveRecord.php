<?php 
namespace common\models;
  /**
     * Returns the database connection used by this AR class.
     * By default, the "db" application component is used as the database connection.
     * This is currently overridden in ccmtCRS as "mysqldb"
     * You may override this method if you want to use a different database connection.
     * @return Connection the database connection used by this AR class.
     */
    

    class MysqlActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * Resurns model id as string
         * @return string
         */
        public static function getDb()
        {
            return Yii::$app->getDb('mysqldb');
        }
    }