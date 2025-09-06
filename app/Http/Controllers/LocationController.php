<?php
// app/Http/Controllers/LocationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    private $baseUrl = 'https://countriesnow.space/api/v0.1/countries';

    /**
     * Get all countries
     */
    public function countries()
    {
        try {
            Log::info('Attempting to fetch countries from API');
            
            $countries = Cache::remember('countries', 3600, function () {
                Log::info('Cache miss - fetching countries from external API', ['url' => $this->baseUrl]);
                
                $response = Http::timeout(30)
                    ->withOptions([
                        'verify' => false, // Disable SSL verification for development
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                        ],
                    ])
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'Mozilla/5.0 (compatible; LaravelApp/1.0)',
                    ])
                    ->get($this->baseUrl);
                
                Log::info('API Response received', [
                    'status' => $response->status(),
                    'successful' => $response->successful(),
                    'body_length' => strlen($response->body())
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('API response structure', [
                        'has_data_key' => isset($data['data']),
                        'data_type' => isset($data['data']) ? gettype($data['data']) : 'not_set',
                        'data_count' => isset($data['data']) && is_array($data['data']) ? count($data['data']) : 0
                    ]);
                    
                    if (isset($data['data']) && is_array($data['data'])) {
                        $countries = collect($data['data'])
                            ->pluck('country')
                            ->filter()
                            ->sort()
                            ->values()
                            ->toArray();
                        
                        Log::info('Successfully processed countries from API', ['count' => count($countries)]);
                        return $countries;
                    } else {
                        Log::warning('API response missing expected data structure', ['response_keys' => array_keys($data)]);
                    }
                } else {
                    Log::error('API request failed', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
                
                Log::info('Falling back to hardcoded countries');
                return $this->getFallbackCountries();
            });

            return response()->json($countries);
            
        } catch (\Exception $e) {
            Log::error('Exception while fetching countries', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json($this->getFallbackCountries());
        }
    }

    /**
     * Get states for a specific country
     */
    public function states($country)
    {
        try {
            Log::info('Attempting to fetch states', ['country' => $country]);
            
            $cacheKey = 'states_' . md5($country);
            
            $states = Cache::remember($cacheKey, 3600, function () use ($country) {
                Log::info('Cache miss - fetching states from external API', [
                    'country' => $country,
                    'url' => $this->baseUrl . '/states'
                ]);
                
                $response = Http::timeout(30)
                    ->withOptions([
                        'verify' => false, // Disable SSL verification for development
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                        ],
                    ])
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'Mozilla/5.0 (compatible; LaravelApp/1.0)',
                    ])
                    ->post($this->baseUrl . '/states', [
                        'country' => $country
                    ]);
                
                Log::info('States API Response received', [
                    'country' => $country,
                    'status' => $response->status(),
                    'successful' => $response->successful(),
                    'body_length' => strlen($response->body())
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('States API response structure', [
                        'country' => $country,
                        'has_data_key' => isset($data['data']),
                        'has_states_key' => isset($data['data']['states']),
                        'response_keys' => array_keys($data),
                        'data_keys' => isset($data['data']) ? array_keys($data['data']) : []
                    ]);
                    
                    if (isset($data['data']['states']) && is_array($data['data']['states'])) {
                        $states = collect($data['data']['states'])
                            ->pluck('name')
                            ->filter()
                            ->sort()
                            ->values()
                            ->toArray();
                        
                        Log::info('Successfully processed states from API', [
                            'country' => $country,
                            'count' => count($states)
                        ]);
                        return $states;
                    } else {
                        Log::warning('States API response missing expected structure', [
                            'country' => $country,
                            'response_sample' => json_encode($data)
                        ]);
                    }
                } else {
                    Log::error('States API request failed', [
                        'country' => $country,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
                
                Log::info('Falling back to hardcoded states', ['country' => $country]);
                return $this->getFallbackStates($country);
            });

            return response()->json($states);
            
        } catch (\Exception $e) {
            Log::error('Exception while fetching states', [
                'country' => $country,
                'message' => $e->getMessage()
            ]);
            return response()->json($this->getFallbackStates($country));
        }
    }

    /**
     * Get cities for a specific country and state
     */
    public function cities($country, $state)
    {
        try {
            Log::info('Attempting to fetch cities', [
                'country' => $country,
                'state' => $state
            ]);
            
            $cacheKey = 'cities_' . md5($country . '_' . $state);
            
            $cities = Cache::remember($cacheKey, 3600, function () use ($country, $state) {
                Log::info('Cache miss - fetching cities from external API', [
                    'country' => $country,
                    'state' => $state,
                    'url' => $this->baseUrl . '/state/cities'
                ]);
                
                $response = Http::timeout(30)
                    ->withOptions([
                        'verify' => false, // Disable SSL verification for development
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                        ],
                    ])
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'Mozilla/5.0 (compatible; LaravelApp/1.0)',
                    ])
                    ->post($this->baseUrl . '/state/cities', [
                        'country' => $country,
                        'state' => $state
                    ]);
                
                Log::info('Cities API Response received', [
                    'country' => $country,
                    'state' => $state,
                    'status' => $response->status(),
                    'successful' => $response->successful(),
                    'body_length' => strlen($response->body())
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Cities API response structure', [
                        'country' => $country,
                        'state' => $state,
                        'has_data_key' => isset($data['data']),
                        'data_type' => isset($data['data']) ? gettype($data['data']) : 'not_set',
                        'data_count' => isset($data['data']) && is_array($data['data']) ? count($data['data']) : 0
                    ]);
                    
                    if (isset($data['data']) && is_array($data['data'])) {
                        $cities = collect($data['data'])
                            ->filter()
                            ->sort()
                            ->values()
                            ->toArray();
                        
                        Log::info('Successfully processed cities from API', [
                            'country' => $country,
                            'state' => $state,
                            'count' => count($cities)
                        ]);
                        return $cities;
                    } else {
                        Log::warning('Cities API response missing expected structure', [
                            'country' => $country,
                            'state' => $state,
                            'response_sample' => json_encode($data)
                        ]);
                    }
                } else {
                    Log::error('Cities API request failed', [
                        'country' => $country,
                        'state' => $state,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
                
                Log::info('Falling back to hardcoded cities', [
                    'country' => $country,
                    'state' => $state
                ]);
                return $this->getFallbackCities($country, $state);
            });

            return response()->json($cities);
            
        } catch (\Exception $e) {
            Log::error('Exception while fetching cities', [
                'country' => $country,
                'state' => $state,
                'message' => $e->getMessage()
            ]);
            return response()->json($this->getFallbackCities($country, $state));
        }
    }

    /**
     * Clear location cache
     */
    public function clearCache()
    {
        try {
            Cache::forget('countries');
            
            // Clear some common state caches
            $commonCountries = ['United States', 'India', 'Canada', 'United Kingdom', 'Australia'];
            foreach ($commonCountries as $country) {
                Cache::forget('states_' . md5($country));
            }
            
            // Clear some common city caches
            $commonStates = [
                'United States' => ['California', 'New York', 'Texas', 'Florida'],
                'India' => ['Maharashtra', 'Karnataka', 'Tamil Nadu', 'Delhi'],
                'Canada' => ['Ontario', 'Quebec', 'British Columbia']
            ];
            
            foreach ($commonStates as $country => $states) {
                foreach ($states as $state) {
                    Cache::forget('cities_' . md5($country . '_' . $state));
                }
            }
            
            Log::info('Location cache cleared successfully');
            
            return response()->json([
                'message' => 'Cache cleared successfully',
                'cleared_items' => [
                    'countries' => 1,
                    'states' => count($commonCountries),
                    'cities' => array_sum(array_map('count', $commonStates))
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to clear cache', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to clear cache'], 500);
        }
    }

    /**
     * Test API connection (for debugging)
     */
    public function testApi()
    {
        $results = [];
        
        // Test countries endpoint
        try {
            $response = Http::timeout(30)
                ->withOptions([
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ],
                ])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; LaravelApp/1.0)',
                ])
                ->get($this->baseUrl);
                
            $results['countries'] = [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 500),
                'ssl_disabled' => true
            ];
        } catch (\Exception $e) {
            $results['countries'] = [
                'error' => $e->getMessage()
            ];
        }
        
        // Test states endpoint with USA
        try {
            $response = Http::timeout(30)
                ->withOptions([
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ],
                ])
                ->post($this->baseUrl . '/states', ['country' => 'United States']);
                
            $results['states'] = [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 500)
            ];
        } catch (\Exception $e) {
            $results['states'] = [
                'error' => $e->getMessage()
            ];
        }
        
        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Fallback countries when API fails
     */
    private function getFallbackCountries()
    {
        return [
            'Afghanistan', 'Albania', 'Algeria', 'Argentina', 'Armenia', 'Australia',
            'Austria', 'Bangladesh', 'Belgium', 'Brazil', 'Bulgaria', 'Canada',
            'Chile', 'China', 'Colombia', 'Croatia', 'Czech Republic', 'Denmark',
            'Egypt', 'Finland', 'France', 'Germany', 'Ghana', 'Greece',
            'Hungary', 'Iceland', 'India', 'Indonesia', 'Ireland', 'Israel',
            'Italy', 'Japan', 'Jordan', 'Kenya', 'South Korea', 'Malaysia',
            'Mexico', 'Morocco', 'Netherlands', 'New Zealand', 'Nigeria', 'Norway',
            'Pakistan', 'Philippines', 'Poland', 'Portugal', 'Romania', 'Russia',
            'Saudi Arabia', 'Singapore', 'South Africa', 'Spain', 'Sri Lanka', 'Sweden',
            'Switzerland', 'Thailand', 'Turkey', 'Ukraine', 'United Arab Emirates',
            'United Kingdom', 'United States', 'Vietnam'
        ];
    }

    /**
     * Fallback states for specific countries when API fails
     */
    private function getFallbackStates($country)
    {
        $states = [
            'United States' => [
                'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado',
                'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho',
                'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
                'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi',
                'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey',
                'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio',
                'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina',
                'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia',
                'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
            ],
            'India' => [
                'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
                'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
                'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
                'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu',
                'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'
            ],
            'Canada' => [
                'Alberta', 'British Columbia', 'Manitoba', 'New Brunswick', 'Newfoundland and Labrador',
                'Northwest Territories', 'Nova Scotia', 'Nunavut', 'Ontario', 'Prince Edward Island',
                'Quebec', 'Saskatchewan', 'Yukon'
            ],
            'Australia' => [
                'Australian Capital Territory', 'New South Wales', 'Northern Territory',
                'Queensland', 'South Australia', 'Tasmania', 'Victoria', 'Western Australia'
            ],
            'United Kingdom' => [
                'England', 'Scotland', 'Wales', 'Northern Ireland'
            ]
        ];

        return $states[$country] ?? [];
    }

    /**
     * Fallback cities for specific countries and states when API fails
     */
    private function getFallbackCities($country, $state)
    {
        $cities = [
            'United States' => [
                'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'Sacramento', 'Oakland', 'Fresno'],
                'New York' => ['New York City', 'Buffalo', 'Rochester', 'Syracuse', 'Albany', 'Yonkers'],
                'Texas' => ['Houston', 'Dallas', 'Austin', 'San Antonio', 'Fort Worth', 'El Paso'],
                'Florida' => ['Miami', 'Tampa', 'Orlando', 'Jacksonville', 'Fort Lauderdale', 'Tallahassee']
            ],
            'India' => [
                'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur', 'Nashik', 'Aurangabad', 'Solapur'],
                'Karnataka' => ['Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum', 'Gulbarga'],
                'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tirunelveli'],
                'Delhi' => ['New Delhi', 'Delhi', 'Gurgaon', 'Faridabad', 'Noida', 'Ghaziabad']
            ],
            'Canada' => [
                'Ontario' => ['Toronto', 'Ottawa', 'Hamilton', 'London', 'Windsor', 'Kingston'],
                'Quebec' => ['Montreal', 'Quebec City', 'Laval', 'Gatineau', 'Longueuil', 'Sherbrooke'],
                'British Columbia' => ['Vancouver', 'Victoria', 'Surrey', 'Burnaby', 'Richmond', 'Abbotsford']
            ]
        ];

        return $cities[$country][$state] ?? [];
    }
}