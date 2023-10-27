<?php

//classe dashboard 
class Dashboard
{
    public $data_inicio;
    public $data_fim;
    //atributos importantes que definem a competência do dashboard
    public $numeroVendas;
    public $totalVendas;
    //métodos get e set
    public function __get($atributo)
    {
        return $this->$atributo;
    }
    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
        return $this;
    }
}

//classe de conexao com o banco
class Conexao
{
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function conectar() //método público de conexão
    {
        try {
            $conexao = new PDO(
                //adicionando a variável $conexao a instancia de PDO
                "mysql:host=$this->host;dbname=$this->dbname",
                //prâmetros de conexão do BD - host + nome do bd
                "$this->user",
                //user
                "$this->pass" //senha 
            );
            //como está sendo trabalhado com o BD e o front-end que utilizam o UTF-8,
            //setando que a comunicação entre o back-end e BD também deve ser tratado através do utf-8
            $conexao->exec('set charset utf8');
            //variável que contém a instância da conexão + exec(do próprio PDO) que executa a instrução para que a instância da conexão trabalhe comm utf-8
            return $conexao;

        } catch (PDOException $e) { //caso ocorra algum erro, recupera a mensagem do mesmo
            echo '<p>' . $e->getMessage() . '</p>';
        }
    }
}

//classe (model)  ---  irá permitir a manipulação do objeto no banco, como se fosse o model para trabalharmos com o projeto
class Bd
{
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard) //método construct recebe conexao e dashboard e são tipados, pois são criados baseados em classes já criadas
    {
        $this->conexao = $conexao->conectar(); //retornando conexão e método conectar
        $this->dashboard = $dashboard;
    }

    public function getNumeroVendas() //será responsável por recuperar o indicador do número de vendas no BD
    {
        $query = 'select count(*) as numero_vendas from tb_vendas where data_venda between :data_inicio AND :data_fim'; //é necessário criar uma query, e ela deve ser uma instrução SQL válido para o server de BD
        $stmt = $this->conexao->prepare($query); //preparo da query - retorna o stmt
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        //recuperando através do this o arquivo dashboard que contém o objeto e através do método get(interno do dashboard), recuperamos o atributo data_inicio e data_fim
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute(); //execução da query

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas; //retorna valor que foi recuperado do banco de dados como objeto
    } //setando a coluna numero_vendas faz com que seja retornada apenas as iformações necessárias, ao invés do objeto completo
    public function getTotalVendas() //será responsável por recuperar o indicador do número de vendas no BD
    {
        $query = 'select SUM(total) as total_vendas from tb_vendas where data_venda between :data_inicio AND :data_fim'; //é necessário criar uma query, e ela deve ser uma instrução SQL válido para o server de BD
        $stmt = $this->conexao->prepare($query); //preparo da query - retorna o stmt
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        //recuperando através do this o arquivo dashboard que contém o objeto e através do método get(interno do dashboard), recuperamos o atributo data_inicio e data_fim
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute(); //execução da query

        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas; //retorna valor que foi recuperado do banco de dados como objeto
    }
}

//instanciando dashboard, conexão e bd que recebe ambos
$dashboard = new Dashboard();
$conexao = new Conexao();

$dashboard->data_inicio = '2018-10-01'; ////passando atributos para o dashboard
$dashboard->data_fim = '2018-10-03'; //passando atributos para o dashboard

$bd = new Bd($conexao, $dashboard);

$dashboard->__set('numeroVendas', $bd->getNumeroVendas()); //setando atributo numeroVendas com base no retorno que obtemos de getNumeroVendas
$dashboard->__set('totalVendas', $bd->getTotalVendas());
print_r($dashboard);

?>