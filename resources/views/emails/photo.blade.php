<html>
<head>

</head>
<body>
<h2>Bonjour {{ $user->name }}</h2>
<p>Veuillez trouver ci-joint la photo {{ $source->photo->title }} de {{ $source->photo->album->user->name }} que vous venez de télécharger.</p>

<img src="{{ $message->embed($source->url) }}" alt="{{ $source->photo->title }}">
</body>
</html>