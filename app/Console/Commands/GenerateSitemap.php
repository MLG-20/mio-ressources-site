<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap';

    public function handle()
    {
        $this->info('Generating sitemap...');

        // Créer le répertoire s'il n'existe pas
        $sitemapDir = storage_path('app/sitemaps');
        if (!File::exists($sitemapDir)) {
            File::makeDirectory($sitemapDir, 0755, true);
        }

        SitemapGenerator::create(config('app.url'))
            ->writeToFile($sitemapDir . '/sitemap.xml');

        $this->info('Sitemap generated successfully at: ' . $sitemapDir . '/sitemap.xml');
    }
}
