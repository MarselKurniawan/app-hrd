<?php
class HRD
{
    private $conn;
    private $table_name = "hrd_table"; // Ganti dengan nama tabel yang sesuai

    public $name;
    public $age;
    public $salary;
    public $years_worked;
    public $employment_status;
    public $position;
    public $contract_type;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (name, age, salary, years_worked, employment_status, position, contract_type) VALUES (:name, :age, :salary, :years_worked, :employment_status, :position, :contract_type)";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':salary', $this->salary);
        $stmt->bindParam(':years_worked', $this->years_worked);
        $stmt->bindParam(':employment_status', $this->employment_status);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':contract_type', $this->contract_type);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
