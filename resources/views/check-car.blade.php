@extends('layouts.app')

@section('title', 'Car Lookup')

@section('content')


    {{-- Recursive render --}}
    @if($car['mot'] != $car['ves'])
        <div class="mt-6 bg-gray-800 p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">VES Data</h2>
            {!! view('components.json-recursive', ['data' => $car['ves'] ?? []])->render() !!}
        </div>
        <div class="mt-6 bg-gray-800 p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">MOT Data</h2>
            {!! view('components.json-recursive', ['data' => $car['mot'] ?? []])->render() !!}
        </div>
    @else
        <div class="max-w-3xl mx-auto text-center text-gray-400">
            <p>Enter a license plate above to check car details.</p>
        </div>
    @endisset
@endsection
