import subprocess
import time
import os

# Nom du fichier MCD
MCD_FILE = "mocodo.mcd"

# Dossier où se trouve le fichier
WORK_DIR = r"./"

print("=== Live Mocodo Server ===")
print(f"Surveillance du fichier : {MCD_FILE}")
print("Appuie sur Ctrl+C pour arrêter.\n")

i = 0

while True:
   try:
      # Exécuter Mocodo
      i += 1
      print(f"[INFO] {i} Génération du schéma...")
      subprocess.run(
         ["python", "-m", "mocodo", "--input", MCD_FILE, "--colors", "brewer+3"],
         cwd=WORK_DIR,
         capture_output=True,
         text=True
      )

      # Attendre 1 seconde avant de recommencer
      time.sleep(1)

   except KeyboardInterrupt:
      print("\n[STOP] Live server arrêté.")
      break
