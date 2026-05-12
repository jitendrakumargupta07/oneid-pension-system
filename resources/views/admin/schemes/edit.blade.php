@extends('layouts.admin')

@section('title', 'Edit Scheme')
@section('page-title', 'Edit Pension Scheme')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.schemes.index') }}">Schemes</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit text-primary"></i> Edit: {{ $scheme->name }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.schemes.update', $scheme) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Scheme Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $scheme->name) }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Scheme Code <span class="text-danger">*</span></label>
                            <input type="text" name="scheme_code" class="form-control @error('scheme_code') is-invalid @enderror"
                                value="{{ old('scheme_code', $scheme->scheme_code) }}" style="font-family:monospace;text-transform:uppercase;">
                            @error('scheme_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $scheme->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Monthly Amount (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="monthly_amount" class="form-control @error('monthly_amount') is-invalid @enderror"
                                    value="{{ old('monthly_amount', $scheme->monthly_amount) }}" min="1" step="0.01">
                            </div>
                            @error('monthly_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Minimum Age <span class="text-danger">*</span></label>
                            <input type="number" name="eligibility_age" class="form-control @error('eligibility_age') is-invalid @enderror"
                                value="{{ old('eligibility_age', $scheme->eligibility_age) }}" min="50" max="100">
                            @error('eligibility_age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', $scheme->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $scheme->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('admin.schemes.index') }}" class="btn btn-outline-primary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
