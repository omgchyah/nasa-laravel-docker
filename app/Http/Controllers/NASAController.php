<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NASAController extends Controller
{
    private $stacApiUrl = "https://earth.gov/ghgcenter/api/stac";
    private $collectionName = "odiac-ffco2-monthgrid-v2023";

    // Function to fetch raw ODIAC data
    public function getOdiacData()
    {
        // Make an HTTP request to the NASA API
        $response = Http::get("{$this->stacApiUrl}/collections/{$this->collectionName}/items?limit=300");

        // Return the data as a JSON response
        return response()->json($response->json());
    }

    // Function to extract CO2 data for heatmap
    public function getHeatmapData()
    {
        // Fetch raw data
        $response = Http::get("{$this->stacApiUrl}/collections/{$this->collectionName}/items?limit=300");
        $data = $response->json();

        // Process the data and extract the bounding box and CO2 data
        $heatmapData = [];

        if (isset($data['features'])) {
            foreach ($data['features'] as $feature) {
                if (isset($feature['assets']['co2-emissions'])) {
                    $bbox = $feature['bbox'] ?? [];
                    $heatmapData[] = [
                        'id' => $feature['id'],
                        'bbox' => $bbox,
                        'co2-emissions' => $feature['assets']['co2-emissions']
                    ];
                }
            }
        }

        // Return processed data as JSON
        return response()->json($heatmapData);
    }
}
