#Instalação

Assim que clonar o sistema, deve-se **copiar os arquivos de outro sistema** já instalado, e fazer neles as alterçaões necessárias:

- admin/lib/defines.php
- admin/lib/db.php
- admin/local-defines.php
- admin/scss/foundation/_variables.scss

##Adição do **dbo** como **fake submodule** na pasta admin

1. Navegar para a pasta admin   
``cd admin``
2. Iniciar um repositório vazio do git  
``git init``
3. Adicionar o dbo como **remote**, com o nome **origin**  
``git remote add origin https://www.github.com/damiansb/dbo.git``
4. Fazer um fetch na branch **master** do dbo  
``git fetch origin master``
5. Forcar um reset com a última versão  
``git reset --hard origin/master``

Após fazer isso, você já pode adicionar os novos arquivos e arquivos modificados ao repositório principal:

1. Navegar para a pasta pai  
``cd ..``
2. Adicionar os arquivos da pasta admin  
**(IMPORTANTE: A barra final é obrigatória para o fake submodule)**  
``git add admin/``

Feito isso, o commit já pode ser dado para atualização do repositório principal.