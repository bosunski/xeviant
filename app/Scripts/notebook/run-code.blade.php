# Writes the code to file
cat > {{ $notebook->getNoteBookFilePath() }} << EOF
{!! $code !!}
EOF

cd {{ $notebook->owner->getUserRoot() }}
php {{ $notebook->getFileName() }}

{{--docker run -v $(pwd):/app ciroue-image php {{ $notebook->getFileName() }}--}}
