<?php

namespace Database\Seeders;

use App\Models\Veiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class VeiculoImagensCarrosSeeder extends Seeder
{
    private const EXTENSOES = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

    /**
     * Percorre public_path('carros')/{nome-do-modelo}/ (e subpastas), encontra imagens
     * e grava o caminho relativo a public/ em veiculos.imagem (ex.: carros/Argo/foto.jpg),
     * para uso com asset(). O modelo do veículo deve coincidir com o nome da pasta.
     */
    public function run(): void
    {
        $raizCarros = public_path('carros');

        if (! is_dir($raizCarros)) {
            $this->command?->warn('Pasta public/carros não encontrada: '.$raizCarros);

            return;
        }

        $publicNormalizado = str_replace('\\', '/', public_path());

        $pastasModelo = array_values(array_filter(
            scandir($raizCarros) ?: [],
            fn (string $nome) => $nome !== '.' && $nome !== '..'
                && is_dir($raizCarros.DIRECTORY_SEPARATOR.$nome)
        ));
        sort($pastasModelo);

        foreach ($pastasModelo as $nomePastaModelo) {
            $caminhoModelo = $raizCarros.DIRECTORY_SEPARATOR.$nomePastaModelo;
            $imagens = $this->listarImagensRecursivo($caminhoModelo);

            if ($imagens === []) {
                continue;
            }

            $veiculos = Veiculo::query()
                ->where('modelo', $nomePastaModelo)
                ->orderBy('id')
                ->get();

            if ($veiculos->isEmpty()) {
                $this->command?->warn("Nenhum veículo com modelo [{$nomePastaModelo}] — imagens ignoradas.");

                continue;
            }

            foreach ($imagens as $indice => $caminhoCompleto) {
                if (! isset($veiculos[$indice])) {
                    break;
                }

                $relativo = Str::after(
                    str_replace('\\', '/', $caminhoCompleto),
                    $publicNormalizado.'/'
                );

                $veiculos[$indice]->update(['imagem' => $relativo]);
            }
        }
    }

    /**
     * @return list<string>
     */
    private function listarImagensRecursivo(string $dir): array
    {
        $arquivos = [];

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $ext = strtolower($file->getExtension());
            if (in_array($ext, self::EXTENSOES, true)) {
                $arquivos[] = $file->getPathname();
            }
        }

        sort($arquivos);

        return $arquivos;
    }
}
