<!doctype html> <!-- Important: must specify -->
<html>
<head>
  <meta charset="utf-8"> <!-- Important: rapi-doc uses utf8 characters -->
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
</html>
