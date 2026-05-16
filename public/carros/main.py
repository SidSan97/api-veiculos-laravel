"""
Percorre as subpastas de public/carros e renomeia arquivos de imagem para
carro.ext, carro_2.ext, carro_3.ext... (ordem alfabética por nome do arquivo).

Uso:
  python main.py           # aplica renomes
  python main.py --dry-run # apenas lista o que seria feito
"""
from __future__ import annotations

import sys
from pathlib import Path

IMAGE_EXTENSIONS = frozenset(
    {".jpg", ".jpeg", ".png", ".gif", ".webp", ".jfif", ".bmp", ".svg", ".avif"}
)


def collect_images(folder: Path) -> list[Path]:
    images: list[Path] = []
    for p in folder.iterdir():
        if not p.is_file():
            continue
        if p.suffix.lower() in IMAGE_EXTENSIONS:
            images.append(p)
    return sorted(images, key=lambda x: x.name.lower())


def plan_renames(folder: Path) -> list[tuple[Path, str]]:
    images = collect_images(folder)
    planned: list[tuple[Path, str]] = []
    for i, src in enumerate(images):
        suffix = "" if i == 0 else f"_{i + 1}"
        dest_name = f"carro{suffix}{src.suffix.lower()}"
        if src.name.lower() == dest_name.lower():
            continue
        planned.append((src, dest_name))
    return planned


def apply_renames(folder: Path, planned: list[tuple[Path, str]]) -> None:
    if not planned:
        return
    tmps: list[Path] = []
    for idx, (src, _) in enumerate(planned):
        tmp = folder / f".rename_stage_{idx}_{src.name}"
        src.rename(tmp)
        tmps.append(tmp)
    for tmp, (_, dest_name) in zip(tmps, planned):
        tmp.rename(folder / dest_name)


def main() -> None:
    dry_run = "--dry-run" in sys.argv or "-n" in sys.argv
    base = Path(__file__).resolve().parent

    total = 0
    for item in sorted(base.iterdir(), key=lambda p: p.name.lower()):
        if not item.is_dir():
            continue
        if item.name.startswith("."):
            continue
        planned = plan_renames(item)
        if not planned:
            continue
        print(f"{item.name}/")
        for src, dest_name in planned:
            prefix = "  [dry-run] " if dry_run else "  "
            print(f"{prefix}{src.name} -> {dest_name}")
        if not dry_run:
            apply_renames(item, planned)
        total += len(planned)

    print(f"Concluído. {'(simulação) ' if dry_run else ''}Renomes: {total}.")


if __name__ == "__main__":
    main()
