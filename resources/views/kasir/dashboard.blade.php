<h1>Halo Kasir, {{ Auth::user()->nama }}</h1>
<p>Ini adalah halaman dashboard kasir.</p>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>
