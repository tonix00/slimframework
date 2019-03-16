<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/customers', function(Request $request, Response $response){
   
   	$customer = Customer::getInstance();
   	$records = $customer->getCustomers();
    return $response->withJson($records);

});

$app->get('/api/customer/{id}', function(Request $request, Response $response){

   	$id = (int) $request->getAttribute('id');
   	$customer = Customer::getInstance();
   	$record = $customer->getCustomer($id);
   	
   	if($record==false){
    	$info['error'] = 'Customer with ID number '.$id.' is not found.';
    	return $response->withJson($info);
    }

   	return $response->withJson($record);
   
});

$app->post('/api/customer/add', function(Request $request, Response $response){

	$data = array();
   	$data['first_name'] = $request->getParam('first_name');
   	$data['last_name'] = $request->getParam('last_name');
   	$data['address'] = $request->getParam('address');
   	$data['city'] = $request->getParam('city');
   	$data['state'] = $request->getParam('state');
   	$data['email'] = $request->getParam('email');
   	$data['phone'] = $request->getParam('phone');
   
   	$customer = Customer::getInstance();
   	$id = $customer->add($data);
    
    if($id === false){
    	$info['error'] = 'Error in adding a customer.';
    	return $response->withJson($info);
    }

    $info['status'] = 'Customer is added with ID Number '.$id.".";
    return $response->withJson($info);
   	
});

$app->put('/api/customer/update/{id}', function(Request $request, Response $response){

	$customer = Customer::getInstance();
	$data = array();
	$data['id'] = (int)$request->getAttribute('id');

	$record = $customer->getCustomer($data['id']);
    if($record==false){
    	$info['error'] = 'Customer with ID number '.$data['id'].' is not found.';
    	return $response->withJson($info);
    }

   	$data['first_name'] = $request->getParam('first_name');
   	$data['last_name'] = $request->getParam('last_name');
   	$data['address'] = $request->getParam('address');
   	$data['city'] = $request->getParam('city');
   	$data['state'] = $request->getParam('state');
   	$data['email'] = $request->getParam('email');
   	$data['phone'] = $request->getParam('phone');
   
   	
   	$flag = $customer->update($data);

   	if($flag === false){
    	$info['error'] = 'Error in updating the record of the customer (ID:'.$data['id'].').';
    	return $response->withJson($info);
    }

    $info['status'] = 'Customer\'s record is successfully updated. This customer has ID '.$data['id'].".";
    return $response->withJson($info);
});

$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response){

   	$id = (int) $request->getAttribute('id');
   	$customer = Customer::getInstance();

    $record = $customer->getCustomer($id);
    if($record==false){
    	$info['error'] = 'Customer with ID number '.$id.' is not found.';
    	return $response->withJson($info);
    }

   	$flag = $customer->delete($id);

   	if($flag === false){
    	$info['error'] = 'Error in deleting the record of the customer (ID:'.$id.').';
    	return $response->withJson($info);
    }

    $info['status'] = 'Customer\'s record is successfully deleted. The customer is ID '.$id.".";
    return $response->withJson($info);
   
});

