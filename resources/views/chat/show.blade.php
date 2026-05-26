<div id="chat-area-content">
<div class="chat-header">
    @php $other = auth()->user()->role === 'trainer' ? $conversation->member : $conversation->trainer; @endphp
    <div class="avatar" style="width:36px;height:36px;background:#E1F5EE;color:#0F6E56;font-size:14px">{{ substr($other->name ?? 'م', 0, 1) }}</div>
    <div><div style="font-size:14px;font-weight:500">{{ $other->name ?? 'مستخدم' }}</div><div style="font-size:11px;color:#1D9E75"><i class="ti ti-circle-filled" style="font-size:8px"></i> متصل الآن</div></div>
</div>
<div class="chat-messages" id="chat-msgs">
@foreach($conversation->messages as $msg)
<div>
    <div class="msg {{ $msg->sender_id === auth()->id() ? 'msg-out' : 'msg-in' }}">
        {{ $msg->message }}
        @if($msg->exercise_data)
        <div class="msg-exercise" style="background:{{ $msg->sender_id === auth()->id() ? 'rgba(255,255,255,0.2)' : 'var(--color-background-secondary)' }}">
            <div style="font-weight:500;color:{{ $msg->sender_id === auth()->id() ? '#fff' : 'var(--color-text-primary)' }}"><i class="ti ti-player-play" style="font-size:13px"></i> {{ $msg->exercise_data['name'] ?? '' }}</div>
            <div style="font-size:11px;color:{{ $msg->sender_id === auth()->id() ? 'rgba(255,255,255,0.8)' : 'var(--color-text-secondary)' }}">{{ $msg->exercise_data['sets'] ?? '' }} مجموعات × {{ $msg->exercise_data['reps'] ?? '' }} تكرار</div>
        </div>
        @endif
        <div class="msg-time" style="color:{{ $msg->sender_id === auth()->id() ? 'rgba(255,255,255,0.7)' : 'var(--color-text-tertiary)' }}">{{ $msg->created_at->format('h:i A') }}</div>
    </div>
</div>
@endforeach
</div>
<div class="chat-input-area">
    <textarea class="chat-input" id="chat-text" rows="1" placeholder="اكتب رسالة..." onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMsg()}"></textarea>
    <button class="btn btn-primary" style="border-radius:50%;width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center" onclick="sendMsg()"><i class="ti ti-send" style="font-size:15px"></i></button>
</div>
</div>
