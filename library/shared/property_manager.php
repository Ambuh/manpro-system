<?php
function property_manager(){
    
    return new propertyHelper;
    
}
class propertyHelper{
    
    public function __construct(){
        
    }
    public function getClientAccountDetails($whereclause=""){
        
    }
    public function creditClientAccount($accountDetails){
        
    }
    public function debitClientAccount($accountDetails){
        
    }
    public function getTenantAccountDetails($whereclause=""){
        
    }
    public function creditTenantAccount($accountDetails){
        
    }
    public function debitTenantAccount($accountDetails){
        
    }
    public function saveClientTransaction($transObj){
        
    }
    public function getClientTransactions($whereclause=""){
        
    }
    public function getTenantTransactions($whereclause=""){
        
    }
    public function saveTenantTransaction($transObj){
        
    }
    public function getProperties($whereclause=""){
        
    }
    public function saveProperty($PropObject){
        
    }
    public function updateProperty($propObject){
        
    }
    public function getPropertyUnits($whereclause=""){
        
    }
    public function savePropertyUnit($unitObject){
        
    }
    public function updatePropertyUnit($unitobj){
        
    }
    public function getPropertyServices($whereclause=""){
        
    }
    public function savePropertyService($serviceObj){
        
    }
    public function updatePropertyService($serviceobj){
        
    }
    public function getTenantOccupies($whereclause=""){
        
    }
    public function saveTenantOccupies($occBject){
        
    }
    public function updateTenantOccupies($occObj){
        
    }
   
}
class ClientAccount{
    public $tenant_id;
    public $transaction_date;
    public $description;
    public $debit_amount;
    public $credit_amount;
    public $balance;
}
class TenantAccount{
    public $tenant_id;
    public $transaction_date;
    public $description;
    public $debit_amount;
    public $credit_amount;
    public $balance;
}
class clientTransaction{
    public $transaction_id;
    public $transaction_clientId;
    public $transaction_date;
    public $transaction_description;
    public $transaction_payMode;
    public $transaction_amount;
}
class tenantTransaction{
    public $transaction_id;
    public $transaction_tenantId;
    public $transaction_date;
    public $transaction_description;
    public $transaction_payMode;
    public $transaction_amount;
    public $transaction_referenceNo;
    public $transaction_propertyId;
    public $transaction_propertyUnit;
}
class property{
    public $property_id;
    public $property_name;
    public $property_owner;
    public $property_units;
    public $property_location;
    public $property_status;
    public $property_added;
    public $property_description;
}
class PropertyService{
    public $service_id;
    public $service_name;
    public $service_cost;
    public $service_property;
    public $service_status;
    public $service_type;
}
class TenantOccupies{
    public $occupy_id;
    public $occupy_unit;
    public $occupy_tenantID;
    public $occupy_rent;
    public $occupy_deposit;
    public $occupy_regdate;
    public $occupy_commenceDate;
}
class PropertyUnits{
    public $unit_id;
    public $unit_name;
    public $unit_propertyId;
    public $unit_propertyOwner;
    public $unit_rentalCost;
    public $unit_status;
    public $unit_depositMultiple;
}
?>