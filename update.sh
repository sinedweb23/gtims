#!/bin/bash

# Diretório do código no sistema Windows
WINDOWS_CODE_DIR="E:/xampp/htdocs/gti"

CHAVE_SSH="E:/xampp/htdocs/gti/ORACLE-GLPI.pem"


# Diretório do código no servidor Linux
LINUX_CODE_DIR="/var/www/morumbisul/glpi/public/gtims"

git describe --tags 

# Solicitar o nome da versão
read -p "Digite o nome da versão: " VERSION

# Solicitar a mensagem de commit
read -p "Digite a mensagem do commit: " COMMIT_MESSAGE

# Comando para entrar no diretório do código no sistema Windows
cd "$WINDOWS_CODE_DIR"

# Exibir status do git
echo "Status do git no diretório $WINDOWS_CODE_DIR:"
git status

# Adicionar todas as alterações para commit
git add .

# Criar tag/version
git tag "$VERSION"

# Fazer commit com a mensagem especificada
git commit -a -m "$COMMIT_MESSAGE"

# Enviar alterações para o repositório remoto
git push origin main --tags

# Acessar o servidor Linux e atualizar o código do repositório e ajustar permissões da pasta
ssh -i  "$CHAVE_SSH" ubuntu@glpi.morumbisul.com.br "cd $LINUX_CODE_DIR && sudo git pull && sudo chown www-data.www-data -R $LINUX_CODE_DIR "


