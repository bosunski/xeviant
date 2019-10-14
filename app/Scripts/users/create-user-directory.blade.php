# Create Directory for User

@php
    $dir = config('filesystems.codeStorage.root') . DIRECTORY_SEPARATOR . $user->username
@endphp

echo "Creating User Directory {{ $dir }}"
mkdir -p {{ $dir }}
