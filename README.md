# IFLeads

Aplicação para gerenciamento de empréstimos de materiais do Instituto Federal do Rio Grande do Norte.

## Regras de Desenvolvimento Para o Projeto

As seguintes regras descrevem padrões de desenvolvimento escolhidos pela equipe de desenvolvedores da aplicação,
visando como resultado qualidade do código gerado, junto a um bom fluxo de desenvolvimento.

### Coding Style

- String

Strings devem ser criadas usando apóstrofos simples.

```
$greeting = 'Hello World!';
```

Caso seja necessário o uso tanto de apóstrofos quanto de variáveis dentro da string, faça uso do apóstrofo composto.

```
$name = 'coffee guy';
$greeting = "Hello World ${name}!"
```

- Array

Arrays com multiplas linhas devem ser indentados das seguintes formas, não há forma de criação de array definidas podendo ser usadas tanto a função de array quanto as colchetes([]):
  ** Forma 1 **

```
$greeting = array(
  'hello', 'world', 10, 'anos', '1',
  2, 3, 4,
  5, 6, 7, 8, 9, 10
);

$greeting = array('hello', 'world', 10, 'anos', '1',
                  2, 3, 4,
                  5, 6, 7, 8, 9, 10);
```

- Classes

Sempre que possível a extensão ou implementação de classes deve ser feita na mesma linha:

```
class DezAnos extends Aluno implements ModoVinteAnos
{
  // Code Goes Here
}
```

Caso a definição de classe ultrapasse o tamanho de linha definido, quebre a linha
adicionando um nível de indentação.

```
class DezAnos
  extends Aluno
  implements ModoVinteAnos
{
  // Code Goes Here
}
```

ou

```
class DezAnos
  implements Aluno
    implements ModoVinteAnos
{
  // Code Goes Here
}
```

- Funções

As chaves das funções, assim como das classes, devem vir abaixo da atribuição da
função.

```
public function ativarModoVinteAnos()
{
  // Code Goes Here
}
```

A identação de argumentos da função deve ser feita das seguintes formas:

```
modoDezAnos($a, $zend, $standard, $esta,
            $sendo, $usada, $como, $referencia
            $desta, $style, $guide)
{
  // Code Goes Here
}
```

```
modoDezAnos(
  $a, $zend, $standard, $esta,
  $sendo, $usada, $como, $referencia
  $desta, $style, \$guide
) {
// Code Goes Here
}
```

A identação dos argumentos passados na chamada dos métodos segue a mesma regra
da identação de arrays. 

#### Versionamento

* Mensagens de Commit

1. Mensagens curtas e objetivas;
2. Commit em inglês (opcional);
3. Usar palavras chaves (add, update, fix, delete, moving, create, etc);
4. Gitmoji (opcional);
5. Sem força commits, ou seja, sem -f.

* Fluxo de Versionamento

1. GitFlow
2. Sem commits na master/develop (FORTEMENTE RECOMENDADO)

##### Ferramentas do Git

* Issues

As Issues serão utilizadas para descrever tanto novas features quanto problemas(bugs). Na sessão de issues já estão definidas por padrão diferentes labels que podem ser úteis na criação de novas issues, porém caso necessário, os colaboradores podem criar novas.

Para que a equipe possa acompanhar o desenvolvimento e o crescimento do projeto, cada issue deve ser atrelada ao Projeto do github projects (o por que disso será informado logo abaixo).

Não haverá designação de tarefas, as issues serão criadas sem responsáveis. Caso um colaborador queira resolver a issue o mesmo deverá se atribuir a ela, dessa maneira especificando que está responsável pela tarefa.

* GITHUB PROJECTS

Por padrão o github oferece diversos templates para a criação da estrutura do projeto, com isso iremos utilizar o template Automated Kanban with Review que automatiza a maioria dos processos que envolvem código. Este template tem quatro quadros: Para fazer (To-do), Em progresso (In progress), Revisão (review) e Terminado (done).
