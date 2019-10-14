@php
    $rootDir = config('filesystems.codeStorage.root') . DIRECTORY_SEPARATOR . $user->username
@endphp

# Create Directory For Code
mkdir {{ $noteDir = $rootDir . DIRECTORY_SEPARATOR . $notebook->id }}

# Create composer.json file
cat > {{ $noteDir . DIRECTORY_SEPARATOR . "/composer.json" }} << EOF
{!! $script->composerBaseConfiguration() !!}
EOF

# Create Dockerfile
cat > {{ $noteDir . DIRECTORY_SEPARATOR . "/Dockerfile" }} << EOF
{!! $script->containerDockerfile() !!}
EOF



# Create Container

# Start Container
