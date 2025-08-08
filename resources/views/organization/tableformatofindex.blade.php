
<table class="table table-hover table-bordered align-middle" style="min-width: 100%;">
    <thead class="table-dark">
        <tr>
            <th scope="col" style="width: 20%;">Sector</th>
            <th scope="col" style="width: 20%;">Division</th>
            <th scope="col" style="width: 30%;">Department</th>
            <th scope="col" style="width: 30%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sectors as $sector)
            @php
                $sectorRowSpan = max(
                    1,
                    $sector->divisions->sum(fn($div) => max(1, $div->departments->count())),
                    $sector->departments->count() > 0 ? $sector->departments->count() : 1
                );
            @endphp

            @if ($sector->divisions->count() > 0)
                @foreach ($sector->divisions as $division)
                    @php
                        $divisionRowSpan = max(1, $division->departments->count());
                    @endphp

                    @if ($division->departments->count() > 0)
                        @foreach ($division->departments as $dept)
                            <tr>
                                {{-- Sector cell with rowspan --}}
                                @if ($loop->first && $loop->parent->first)
                                    <td rowspan="{{ $sectorRowSpan }}" class="align-middle text-primary fw-semibold" style="vertical-align: middle;">
                                        {{ $sector->name }}
                                        <div class="mt-3 d-flex gap-2">
                                            <a href="{{ route('organization.sector.edit', $sector) }}" class="btn btn-sm btn-outline-warning flex-grow-1" title="Edit Sector">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('organization.sector.destroy', $sector) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sector? This action cannot be undone!');" style="flex-grow: 1;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Delete Sector">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif

                                {{-- Division cell with rowspan --}}
                                @if ($loop->first)
                                    <td rowspan="{{ $divisionRowSpan }}" class="align-middle" style="vertical-align: middle;">
                                        {{ $division->name }}
                                        <div class="mt-3 d-flex gap-2">
                                            <a href="{{ route('organization.division.edit', $division) }}" class="btn btn-sm btn-outline-warning flex-grow-1" title="Edit Division">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('organization.division.destroy', $division) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this division? This action will remove all its departments!');" style="flex-grow: 1;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Delete Division">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif

                                {{-- Department name --}}
                                <td>{{ $dept->name }}</td>

                                {{-- Department actions --}}
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('organization.department.edit', $dept) }}" class="btn btn-sm btn-outline-warning flex-grow-1" title="Edit Department">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('organization.department.destroy', $dept) }}" method="POST" class="d-inline flex-grow-1" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Delete Department">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        {{-- Division with no departments --}}
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $sectorRowSpan }}" class="align-middle text-primary fw-semibold" style="vertical-align: middle;">
                                    {{ $sector->name }}
                                    <div class="mt-3 d-flex gap-2">
                                        <a href="{{ route('organization.sector.edit', $sector) }}" class="btn btn-sm btn-outline-warning flex-grow-1" title="Edit Sector">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('organization.sector.destroy', $sector) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sector?');" style="flex-grow: 1;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Delete Sector">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                            <td class="align-middle" style="vertical-align: middle;">
                                {{ $division->name }}
                                <div class="mt-3 d-flex gap-2">
                                    <a href="{{ route('organization.division.edit', $division) }}" class="btn btn-sm btn-outline-warning flex-grow-1" title="Edit Division">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('organization.division.destroy', $division) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this division? This action will remove all its departments!');" style="flex-grow: 1;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Delete Division">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-muted fst-italic">No departments</td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
            @else
                {{-- No divisions, departments directly under sector --}}
                @if ($sector->departments->count() > 0)
                    @foreach ($sector->departments as $dept)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $sector->departments->count() }}" class="align-middle text-primary fw-semibold" style="vertical-align: middle;">
                                    {{ $sector->name }}
                                    <div class="mt-3 d-flex gap-2">
                                        <a href="{{ route('organization.sector.edit', $sector) }}" class="btn btn-sm btn-outline-warning flex-grow-1" title="Edit Sector">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('organization.sector.destroy', $sector) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sector?');" style="flex-grow: 1;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Delete Sector">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                            <td class="text-muted fst-italic">No divisions</td>
                            <td>{{ $dept->name }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('organization.department.edit', $dept) }}" class="btn btn-sm btn-outline-warning flex-grow-1" title="Edit Department">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('organization.department.destroy', $dept) }}" method="POST" class="d-inline flex-grow-1" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Delete Department">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    {{-- Empty sector --}}
                    <tr>
                        <td class="text-primary fw-semibold">{{ $sector->name }}</td>
                        <td class="text-muted fst-italic">No divisions</td>
                        <td class="text-muted fst-italic">No departments</td>
                        <td></td>
                    </tr>
                @endif
            @endif
        @endforeach
    </tbody>
</table>