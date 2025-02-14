@extends('content/general/layouts/horizontalLayout')

@section('title', 'Buat Tugas')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
@endsection

@section('content')
    <div class="col text-center">
        <h2>Lihat Penugasan</h2>
    </div>
    <div class="form-group col-lg-6 mx-auto">
        <label for="title">Judul Tugas</label>
        <input type="text" required class="form form-control" disabled name="title" id="title"
            value="{{ old('title', $task->title) }}">
    </div>
    <div class="form-group col-lg-6 mx-auto mt-2">
        <label for="description">Deskripsi Tugas</label>
        <textarea class="form form-control" required rows="5" disabled id="description" name="description">{{ old('description', $task->description) }}</textarea>
    </div>
    <div class="form-group col-lg-6 mx-auto mt-2">
        <label for="deadline">Deadline</label>
        <input type="datetime-local" required class="form form-control" disabled name="deadline" id="deadline"
            value="{{ old('deadline', $task->deadline) }}">
    </div>
    <div class="col-lg-6 mx-auto mt-4">
        @if ($task->file == null)
            <p class="text-danger">Tidak ada dokumen pendukung</p>
        @else
            <a id="file" class="btn btn-primary me-2" href="{{ route('teacher.task.download', $task->file) }}">Unduh
                dokumen pendukung disini</a>
        @endif
        <a href="{{ route('teacher.task.index') }}" class="btn btn-danger">Kembali</a>
    </div>
    </div>
    <!-- /.container-fluid -->
@endsection
