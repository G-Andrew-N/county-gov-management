<?php

class User {
    private $id;
    private $email;
    private $ssn;
    private $department;
    private $subDepartment;
    private $isActive;
    private $createdAt;
    private $updatedAt;

    public function __construct($email, $ssn, $department, $subDepartment) {
        $this->email = $email;
        $this->ssn = $ssn;
        $this->department = $department;
        $this->subDepartment = $subDepartment;
        $this->isActive = false; // Default to inactive until approved
        $this->createdAt = date("Y-m-d H:i:s");
        $this->updatedAt = date("Y-m-d H:i:s");
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSsn() {
        return $this->ssn;
    }

    public function getDepartment() {
        return $this->department;
    }

    public function getSubDepartment() {
        return $this->subDepartment;
    }

    public function isActive() {
        return $this->isActive;
    }

    public function activate() {
        $this->isActive = true;
        $this->updatedAt = date("Y-m-d H:i:s");
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setId($id) {
        $this->id = $id;
    }
}