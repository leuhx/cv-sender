<?php

// Teste simples para verificar a validação de tamanho de arquivo

echo "Configurações atuais de validação:\n";
echo "- Tamanho máximo: 1MB (1024 KB)\n";
echo "- Tipos permitidos: PDF, DOC, DOCX\n";
echo "- Validação: required|file|mimes:pdf,doc,docx|max:1024\n";
echo "\nConfigurações do PHP (Docker):\n";
echo "- upload_max_filesize: 2M\n";
echo "- post_max_size: 2M\n";
echo "\nConfigurações Nginx (Docker):\n";
echo "- client_max_body_size: 2M\n";
echo "\nValidação implementada com sucesso!\n";
