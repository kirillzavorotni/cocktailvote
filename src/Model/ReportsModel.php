<?php

class ReportsModel
{
    private $db_conf;
    private $connection;
    private $admin_conf;

    public function __construct()
    {
        $this->db_conf = include "../config/db_conf.php";
        $this->admin_conf = include "../config/adminConfig.php";


        $this->openPDOConnection();
    }

    public function getVotesReport()
    {
        $adminPass = $this->admin_conf["adminPass"];
        $sql = "
                SELECT product.name, COUNT(vote.product_id) AS VoteCount
                FROM product JOIN vote
                ON product.id = vote.product_id
                GROUP BY vote.product_id
                ORDER BY product.name 
                DESC;
            ";
        $res = null;

        if (isset($_POST) && isset($_POST["password"]) && $_POST["password"] === $adminPass) {
            $res = $this->makeQuery($sql);
            $this->CloseConnection();
        }

        echo include "../public/templates/voteReport.php";
    }


    ////////////////////////////////////////////////////////////////////////////////////////////
    private function openPDOConnection()
    {
        try {
            $this->connection = new PDO(
                $this->db_conf["dsn"],
                $this->db_conf["user"],
                $this->db_conf["pass"]
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    private function makeQuery($sql)
    {
        $res = [];
        $stmt = $this->connection->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }

        return $res;
    }

    private function CloseConnection()
    {
        $this->connection = null;
    }
}