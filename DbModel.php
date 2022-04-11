<?php

namespace app\core;

// Model of Object relational mapping

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;

    abstract public function attributes(): array;

    //abstract public function primaryKey(): string;
    public static function primaryKey(): string
    {
        return 'id';
    }

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(fn($attr) => ":$attr", $attributes);

        $statement = self::prepare("INSERT INTO $tableName(".implode(',', $attributes).")
                    VALUES(".implode(',', $params).")"); //<- These are the values we will bind

        foreach($attributes as $attribute)
        {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();

        return true; // provided no exception / errors and statement executes correctly  - could implement try catch
    }

    public static function findOne($where) // Declare static to avoid statically calling non-static method
    {
        //$tableName = (new UserModel)->tableName(); - cant instantiate an abstract class
        $tableName = static::tableName();
        $attributes = array_keys($where);
        // SELECT * FROM $tableName WHERE email = :email AND firstname = :firstname
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes)); // space after AND i think

        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach($where as $key => $item)
        {
            $statement->bindValue(":$key", $item );
        }
        $statement->execute();

        return $statement->fetchObject(static::class);//Want fetchObj to return an instance of the User class, - on which findOne is called
    }


    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}