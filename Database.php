<?php

namespace app\core;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // if problem with PDO - throw an exception
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration)
        {
            if($migration === '.' || $migration === '..' )
            {
                continue;
            }
            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className(); // Instantiating migration class into an object
              $this->log("Applying migration $migration");
            $instance->up();
              $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }
        if (!empty($newMigrations))
        {
            $this->saveMigrations($newMigrations);
        } else {
            //echo "All Migrations are Applied";
            $this->log("All Migrations are Applied");
        }
        //var_dump($files);
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
    ) ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN); // fetch every migration column values as single dimensional array
    }

    public function saveMigrations(array $migrations)
    {
        $str = implode(",",array_map(fn($m)=>"('$m')", $migrations));
       $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
                           $str ");
       $statement->execute();
    }

    // PREPARE SQL
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    //LOG DATE TIME
    protected function log($message)
    {
        echo '['.date('Y-m-d H:i:s').']-'.$message.PHP_EOL;
    }


//END OF DATABASE CLASS
}