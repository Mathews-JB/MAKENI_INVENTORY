<?php
class CurrencyConverter extends Model {
    private $apiKey = 'YOUR_API_KEY'; // Free API: exchangerate-api.com or fixer.io
    private $baseCurrency = 'ZMW'; // Zambian Kwacha
    
    public function __construct() {
        parent::__construct();
    }

    // Get all currency rates
    public function getAllRates() {
        $this->db->query('SELECT * FROM currency_rates ORDER BY currency_code ASC');
        return $this->db->resultSet();
    }

    // Get rate for specific currency
    public function getRate($currencyCode) {
        $this->db->query('SELECT * FROM currency_rates WHERE currency_code = :code');
        $this->db->bind(':code', strtoupper($currencyCode));
        return $this->db->single();
    }

    // Convert amount from one currency to ZMW
    public function convertToZMW($amount, $fromCurrency) {
        if (strtoupper($fromCurrency) == 'ZMW') {
            return $amount;
        }

        $rate = $this->getRate(strtoupper($fromCurrency));
        
        if (!$rate) {
            // If rate not found, try to fetch it
            $this->updateRates();
            $rate = $this->getRate(strtoupper($fromCurrency));
        }

        if ($rate) {
            return $amount * $rate->rate_to_zmw;
        }

        return $amount; // Return original if conversion fails
    }

    // Convert from ZMW to another currency
    public function convertFromZMW($amount, $toCurrency) {
        if (strtoupper($toCurrency) == 'ZMW') {
            return $amount;
        }

        $rate = $this->getRate(strtoupper($toCurrency));
        
        if ($rate && $rate->rate_to_zmw > 0) {
            return $amount / $rate->rate_to_zmw;
        }

        return $amount;
    }

    // Update exchange rates from API
    public function updateRates() {
        try {
            // Using exchangerate-api.com (free tier available)
            // Alternative: https://api.exchangerate-api.com/v4/latest/USD
            $apiUrl = "https://api.exchangerate-api.com/v4/latest/USD";
            
            $response = @file_get_contents($apiUrl);
            
            if ($response === false) {
                return false;
            }

            $data = json_decode($response, true);
            
            if (!isset($data['rates'])) {
                return false;
            }

            // Get USD to ZMW rate
            $usdToZmw = isset($data['rates']['ZMW']) ? $data['rates']['ZMW'] : 27.50;

            // Update rates in database
            foreach ($data['rates'] as $code => $rateToUsd) {
                // Calculate rate to ZMW
                // If 1 USD = 27.50 ZMW and 1 USD = 0.85 EUR
                // Then 1 EUR = 27.50 / 0.85 = 32.35 ZMW
                $rateToZmw = $usdToZmw / $rateToUsd;

                $this->db->query('INSERT INTO currency_rates (currency_code, rate_to_zmw) 
                                 VALUES (:code, :rate) 
                                 ON DUPLICATE KEY UPDATE rate_to_zmw = :rate, last_updated = NOW()');
                $this->db->bind(':code', $code);
                $this->db->bind(':rate', $rateToZmw);
                $this->db->execute();
            }

            // Ensure ZMW is always 1:1
            $this->db->query('INSERT INTO currency_rates (currency_code, currency_name, rate_to_zmw) 
                             VALUES ("ZMW", "Zambian Kwacha", 1.000000) 
                             ON DUPLICATE KEY UPDATE rate_to_zmw = 1.000000');
            $this->db->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Check if rates need updating (older than 24 hours)
    public function needsUpdate() {
        $this->db->query('SELECT MAX(last_updated) as last_update FROM currency_rates WHERE currency_code != "ZMW"');
        $result = $this->db->single();
        
        if (!$result || !$result->last_update) {
            return true;
        }

        $lastUpdate = strtotime($result->last_update);
        $now = time();
        $hoursSinceUpdate = ($now - $lastUpdate) / 3600;

        return $hoursSinceUpdate >= 24;
    }

    // Auto-update rates if needed
    public function autoUpdate() {
        if ($this->needsUpdate()) {
            return $this->updateRates();
        }
        return true;
    }

    // Update specific currency rate
    public function updateCurrencyRate($currencyCode, $rate) {
        $this->db->query('INSERT INTO currency_rates (currency_code, rate_to_zmw) 
                         VALUES (:code, :rate) 
                         ON DUPLICATE KEY UPDATE rate_to_zmw = :rate');
        $this->db->bind(':code', strtoupper($currencyCode));
        $this->db->bind(':rate', $rate);
        return $this->db->execute();
    }
}
