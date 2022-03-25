<!--<!doctype html> 
<html>
<head>
  <meta charset="utf-8"> 
  <script type="module" src="https://unpkg.com/rapidoc/dist/rapidoc-min.js"></script>
</head>
<body>
  <rapi-doc
    spec-url="{{asset('apiDoc/data.yaml')}}"
    theme = "graynav"
    render-style = "read"
    id = "thedoc"

    primary-color="#f54c47"
    nav-color="#3e4b54"
   
    allow-authentication = "true" 
    allow-server-selection = "false"	
    
    show-info = "true"
    show-header = "false"
    schema-style="table"

    

  > </rapi-doc>
</body>
</html>-->
<!DOCTYPE html>
<html>
  <head>
    <title>Redoc</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700|Roboto:300,400,700" rel="stylesheet">

    
    <style>
      body {
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <redoc spec-url='{{asset('apiDoc/data.yaml')}}'></redoc>
    <script src="https://cdn.jsdelivr.net/npm/redoc@latest/bundles/redoc.standalone.js"> </script>
  </body>
</html>
