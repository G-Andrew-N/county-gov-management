<?php

class Document {
    private $id;
    private $userId;
    private $documentType;
    private $filePath;
    private $uploadedAt;
    private $approved;

    public function __construct($userId, $documentType, $filePath) {
        $this->userId = $userId;
        $this->documentType = $documentType;
        $this->filePath = $filePath;
        $this->uploadedAt = date("Y-m-d H:i:s");
        $this->approved = false; // Default to not approved
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getDocumentType() {
        return $this->documentType;
    }

    public function getFilePath() {
        return $this->filePath;
    }

    public function getUploadedAt() {
        return $this->uploadedAt;
    }

    public function isApproved() {
        return $this->approved;
    }

    public function approve() {
        $this->approved = true;
    }

    public function save() {
        // Logic to save the document details to the database
    }

    public static function findByUserId($userId) {
        // Logic to retrieve documents by user ID from the database
    }

    public static function findById($id) {
        // Logic to retrieve a document by its ID from the database
    }
}