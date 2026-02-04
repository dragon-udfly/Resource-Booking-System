@forelse($sentMemos as $memo)
    <tr>
        <td>{{ \Carbon\Carbon::parse($memo->date_created)->format('Y-m-d') }}</td>
        <td>
            @if($memo->receiver)
                {{ $memo->receiver->designation }} - {{ $memo->receiver->first_name }}
            @else
                <span class="text-muted">Unknown</span>
            @endif
        </td>
        <td>{{ Str::limit($memo->subject, 50) }}</td>
        <td>
            @if($memo->status == 2)
                <span class="badge badge-warning">Pending</span>
            @elseif($memo->status == 1)
                <span class="badge badge-success">OK / Agreed</span>
            @elseif($memo->status == 0)
                <span class="badge badge-danger">No / Disagreed</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center" style="padding: 30px; color: #777;">No sent memos.</td>
    </tr>
@endforelse