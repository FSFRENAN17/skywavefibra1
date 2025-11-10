import os

# --- CONFIGURAÇÃO ---
CAMINHO_RAIZ_PROJETO = r"C:\XAMPP\HTDOCS\SKYWAVEFIBRA"

# 2. AGORA VAI: Lista das pastas EXATAS que contêm o SEU código.
PASTAS_PARA_MAPEAR = [
    "source",
    os.path.join("themes", "app") # Mapeia 'themes/app', ignorando 'themes/metronic'
]

# 3. Nome do arquivo de saída
ARQUIVO_SAIDA = "mapa_FINALMENTE_LIMPO.txt"
# --------------------

# Monta o caminho completo do arquivo de saída
caminho_saida_completo = os.path.join(CAMINHO_RAIZ_PROJETO, ARQUIVO_SAIDA)

try:
    with open(caminho_saida_completo, 'w', encoding='utf-8') as f:
        print(f"Iniciando análise CIRÚRGICA em: {CAMINHO_RAIZ_PROJETO}\n")
        f.write(f"Mapa da Estrutura do Projeto (Focado)\n")
        f.write(f"Analisando apenas: {', '.join(PASTAS_PARA_MAPEAR)}\n")
        f.write("="*50 + "\n\n")

        for pasta_essencial in PASTAS_PARA_MAPEAR:
            caminho_pasta_completa = os.path.join(CAMINHO_RAIZ_PROJETO, pasta_essencial)
            
            print(f"--- Mapeando pasta: {pasta_essencial} ---")
            f.write(f"### PASTA RAIZ: {pasta_essencial} ###\n\n")

            if not os.path.exists(caminho_pasta_completa):
                print(f"  AVISO: Pasta não encontrada: {caminho_pasta_completa}")
                f.write("  (Pasta não encontrada)\n\n")
                continue

            for root, dirs, files in os.walk(caminho_pasta_completa, topdown=True):
                # Remove pastas de lixo, se ainda existir alguma
                dirs[:] = [d for d in dirs if d not in ['.git', '__pycache__', 'node_modules', 'vendor']]

                caminho_relativo = os.path.relpath(root, caminho_pasta_completa)
                
                if caminho_relativo == ".":
                    nivel = 0
                else:
                    nivel = caminho_relativo.count(os.sep) + 1
                
                indentacao = "    " * nivel

                if caminho_relativo != ".":
                    f.write(f"{indentacao}[{os.path.basename(root)}]{os.sep}\n")
                
                indentacao_arquivo = "    " * (nivel + 1)
                
                for file in files:
                    f.write(f"{indentacao_arquivo}- {file}\n")
            
            f.write("\n" + "="*50 + "\n\n")

    print(f"\n[SUCESSO] Mapa do projeto salvo em:")
    print(f"{caminho_saida_completo}")

except FileNotFoundError:
    print(f"\n[ERRO] O caminho raiz do projeto não foi encontrado: {CAMINHO_RAIZ_PROJETO}")
except Exception as e:
    print(f"[ERRO] Ocorreu um problema inesperado: {e}")