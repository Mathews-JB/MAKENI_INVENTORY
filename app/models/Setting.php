<?php
class Setting extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Get setting by key
    public function get($key, $default = null) {
        $this->db->query('SELECT setting_value FROM settings WHERE setting_key = :key');
        $this->db->bind(':key', $key);
        
        $result = $this->db->single();
        return $result ? $result->setting_value : $default;
    }

    // Set setting value
    public function set($key, $value) {
        $this->db->query('INSERT INTO settings (setting_key, setting_value) 
                         VALUES (:key, :value) 
                         ON DUPLICATE KEY UPDATE setting_value = :value');
        $this->db->bind(':key', $key);
        $this->db->bind(':value', $value);
        
        return $this->db->execute();
    }

    // Get all settings
    public function getAll() {
        $this->db->query('SELECT * FROM settings ORDER BY setting_key ASC');
        return $this->db->resultSet();
    }

    // Get settings as key-value array
    public function getAllAsArray() {
        $settings = $this->getAll();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->setting_key] = $setting->setting_value;
        }
        return $result;
    }
}
