@forelse($receivedMemos as $memo)
    <tr class="">
        <td>{{ \Carbon\Carbon::parse($memo->date_created)->format('Y-m-d') }}</td>
        <td>
            @if($memo->sender)
                {{ $memo->sender->designation }} - {{ $memo->sender->first_name }}
            @else
                <span class="text-muted">Unknown Sender</span>
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
        <td>
            <button class="btn btn-outline-primary view-memo-btn" data-id="{{ $memo->id }}">Open</button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center" style="padding: 30px; color: #777;">
            No received memos found.
        </td>
    </tr>
@endforelse