<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/todo', function(Request $request, Response $response){
   
   	$todo = Todo::getInstance();
   	$records = $todo->getTodos();
    return $response->withJson($records);

});

$app->get('/api/todo/{id}', function(Request $request, Response $response){

   	$id = (int) $request->getAttribute('id');
   	$todo = Todo::getInstance();
   	$record = $todo->getTodo($id);
   	
   	if($record==false){
    	$info['error'] = 'Todo with ID number '.$id.' is not found.';
    	return $response->withJson($info);
    }

   	return $response->withJson($record);
   
});

$app->post('/api/todo/add', function(Request $request, Response $response){

	  $data = array();
   	$data['title'] = $request->getParam('title');
   	$data['completed'] = (int)$request->getParam('completed');
   
   	$todo = Todo::getInstance();
   	$id = $todo->add($data);
    
    if($id === false){
    	$info['error'] = 'Error in adding a todo.';
    	return $response->withJson($info);
    }

    $data['id'] = $id;
    return $response->withJson($data);
   	
});

$app->put('/api/todo/update/{id}', function(Request $request, Response $response){

	$todo = Todo::getInstance();
	$data = array();
	$data['id'] = (int)$request->getAttribute('id');

	$record = $todo->getTodo($data['id']);
    if($record==false){
    	$info['error'] = 'Todo with ID number '.$data['id'].' is not found.';
    	return $response->withJson($info);
    }

   	$data['title'] = $request->getParam('title');
   	$data['completed'] = (int)$request->getParam('completed');
   	
   	$flag = $todo->update($data);

   	if($flag === false){
    	$info['error'] = '11Error in updating the record of the todo (ID:'.$data['id'].').';
    	return $response->withJson($info);
    }

    $info['status'] = 'Todo\'s record is successfully updated. This todo has ID '.$data['id'].".";
    return $response->withJson($data);
});

$app->delete('/api/todo/delete/{id}', function(Request $request, Response $response){

   	$id = (int) $request->getAttribute('id');
   	$todo = Todo::getInstance();

    $record = $todo->getTodo($id);
    if($record==false){
    	$info['error'] = 'Todo with ID number '.$id.' is not found.';
    	return $response->withJson($info);
    }

   	$flag = $todo->delete($id);

   	if($flag === false){
    	$info['error'] = 'Error in deleting the record of the todo (ID:'.$id.').';
    	return $response->withJson($info);
    }

    $info['status'] = 'Todo\'s record is successfully deleted. The todo is ID '.$id.".";
    return $response->withJson($info);
   
});

