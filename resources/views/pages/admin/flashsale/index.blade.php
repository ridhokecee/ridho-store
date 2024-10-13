@extends('layouts.admin.main') 
@section('title', 'Admin Flash Sale') 

@section('content') 
<div class="main-content"> 
    <section class="section"> 
        <div class="section-header"> 
            <h1>Flash Sale</h1> 
            <div class="section-header-breadcrumb"> 
                <div class="breadcrumb-item active">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </div> 
                <div class="breadcrumb-item">Flash Sale</div> 
            </div> 
        </div> 
        <a href="{{ route('flashsale.create') }}" class="btn btn-icon icon-left btn-primary">
            <i class="fas fa-plus"></i> Flash Sale
        </a> 
        <div class="card-body"> 
            <div class="table-responsive"> 
                <table class="table table-bordered table-md"> 
                        <tr> 
                            <th>#</th> 
                            <th>Nama Flash Sale</th> 
                            <th>Harga Flash Sale</th> 
                            <th>Action</th> 
                        </tr> 
                    <tbody>
                    @php 
                        $no = 0;
                    @endphp
                    @forelse ($flashsales as $item) 
                        <tr> 
                            <td>{{ $no += 1}}</td> 
                            <td>{{ $item->name }}</td> 
                            <td>{{ $item->price }}</td> 
                            <td> 
                                <a href="{{ route('flashsale.detail', $item->id) }}" class="badge badge-info">Detail</a> 
                                <a href="{{ route('flashsale.edit', $item->id) }}" class="badge badge-warning">Edit</a>
                                <a href="{{ route('flashsale.delete', $item->id) }}" class="badge badge-danger"data-confirm-delete="true">Hapus</a> 
                            </td> 
                        </tr> 
                    @empty 
                        <tr>
                            <td colspan="5" class="text-center">Data Flash Sale Kosong</td> 
                        </tr>
                    @endforelse 
                    </tbody>
                </table> 
            </div> 
        </div> 
    </section> 
</div> 
@endsection