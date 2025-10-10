<?php return array (
  'providers' => 
  array (
    0 => 'Backpack\\Basset\\BassetServiceProvider',
    1 => 'Laravel\\Sail\\SailServiceProvider',
    2 => 'Laravel\\Tinker\\TinkerServiceProvider',
    3 => 'Mews\\Purifier\\PurifierServiceProvider',
    4 => 'Carbon\\Laravel\\ServiceProvider',
    5 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    6 => 'Termwind\\Laravel\\TermwindServiceProvider',
    7 => 'Spatie\\LaravelIgnition\\IgnitionServiceProvider',
    8 => 'Mews\\Purifier\\PurifierServiceProvider',
    9 => 'App\\Providers\\AppServiceProvider',
  ),
  'eager' => 
  array (
    0 => 'Backpack\\Basset\\BassetServiceProvider',
    1 => 'Mews\\Purifier\\PurifierServiceProvider',
    2 => 'Carbon\\Laravel\\ServiceProvider',
    3 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    4 => 'Termwind\\Laravel\\TermwindServiceProvider',
    5 => 'Spatie\\LaravelIgnition\\IgnitionServiceProvider',
    6 => 'Mews\\Purifier\\PurifierServiceProvider',
    7 => 'App\\Providers\\AppServiceProvider',
  ),
  'deferred' => 
  array (
    'Laravel\\Sail\\Console\\InstallCommand' => 'Laravel\\Sail\\SailServiceProvider',
    'Laravel\\Sail\\Console\\PublishCommand' => 'Laravel\\Sail\\SailServiceProvider',
    'command.tinker' => 'Laravel\\Tinker\\TinkerServiceProvider',
  ),
  'when' => 
  array (
    'Laravel\\Sail\\SailServiceProvider' => 
    array (
    ),
    'Laravel\\Tinker\\TinkerServiceProvider' => 
    array (
    ),
  ),
);