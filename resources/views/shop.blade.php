@extends('layouts.app')

@section('title', 'Hotels - ACES Lacrosse')

@section('content')
<style>
.team-store-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 50vh; /* just enough height to center the box nicely */
    padding: 60px 20px 40px; /* balanced spacing, not too tall */
    margin: 0;
    background: #fff;
}

.team-store-box {
    border: 2px solid #5620b3ff;
    border-radius: 8px;
    padding: 50px 80px;
    text-align: center;
    max-width: 1200px;
    width: 90%;
    margin: 0 auto;
}

.team-store-text {
    color: #5620b3ff;
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin: 0;
}

@media screen and (min-width: 2560px) {
  .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
      max-width: 230rem;
  }
  .team-store-box {
    max-width: fit-content;
  }
  .team-store-text {
    font-size: 10rem;
  }

}


</style>
<div class="team-store-wrapper">
    <div class="team-store-box">
        <h1 class="team-store-text">TEAM STORE COMING SOON</h1>
    </div>
</div>

@endsection