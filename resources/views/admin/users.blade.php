@extends('admin.layout')

@section('content')
<h1 class="text-2xl font-extrabold text-gray-800 mb-4">Data Pengguna</h1>
<div class="bg-white rounded-xl shadow overflow-x-auto">
	<table class="min-w-full text-sm">
		<thead class="bg-gray-100 text-left">
			<tr>
				<th class="p-3">No Id</th>
				<th class="p-3">Nama</th>
				<th class="p-3">Email</th>
				<th class="p-3">Role</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $u)
			<tr class="border-t">
				<td class="p-3 text-gray-500">{{ $u->id }}</td>
				<td class="p-3">{{ $u->name }}</td>
				<td class="p-3 text-gray-600">{{ $u->email }}</td>
				<td class="p-3">
					<span class="px-2 py-1 rounded-full text-white text-xs {{ $u->role==='A' ? 'bg-blue-600' : 'bg-gray-600' }}">
						{{ $u->role==='A' ? 'Admin' : 'User' }}
					</span>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endsection


