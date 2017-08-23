# brube-conn
A coleção de classes basea-se em facilitar a inserção, consulta e deleção de informações do banco de dados.

## Conn
Classe principal para conexão ao banco de dados usando o padrão SingleTon juntamente com o pacote PDO nativo do PHP

## Create

Classe para <strong>CADASTRAR</strong> registro em banco de dados de acordo com a tabela
```php
    include "{path}/config.php"
    
    $data = [
        'column1' => 'value1',
        'column2' => 'value2',
    ]
    
    $create = new Conn\Create();
    $create->run('table_name', $data);
```

## Read
Classe para <strong>LER</strong> registro em banco de dados de acordo com a tabela
```php
    include "{path}/config.php"
    
    $create = new Conn\Read();
    $create->run('table_name', "WHERE id = :id", "id=1");
```

## Update
Classe para <strong>ATUALIZAR</strong> registro em banco de dados de acordo com a tabela
```php
    include "{path}/config.php"
    
    $data = [
        'column1' => 'value1',
        'column2' => 'value2',
    ]
    
    $create = new Conn\Update();
    $create->run('table_name', $data, "WHERE id = :id", "id=1");
```

## Delete
Classe para <strong>DELETAR</strong> registro em banco de dados de acordo com a tabela
```php
    include "{path}/config.php"
    
    $create = new Conn\Delete();
    $create->run('table_name', "WHERE id = :id", "id=1");
```