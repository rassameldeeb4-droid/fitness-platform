@extends('layouts.app')
@section('title', 'المحادثات')
@push('styles')
<style>
.chat-wrap{display:flex;gap:0;height:calc(100vh - 160px);background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);overflow:hidden}
.chat-list{width:260px;border-left:0.5px solid var(--color-border-tertiary);overflow-y:auto;flex-shrink:0}
.chat-list-item{padding:12px 14px;cursor:pointer;border-bottom:0.5px solid var(--color-border-tertiary);display:flex;align-items:center;gap:10px}
.chat-list-item:hover{background:var(--color-background-secondary)}
.chat-list-item.active{background:#E1F5EE}
.chat-list-name{font-size:13px;font-weight:500}
.chat-list-last{font-size:11px;color:var(--color-text-secondary);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px}
.chat-unread{background:#1D9E75;color:#fff;border-radius:10px;font-size:10px;padding:1px 6px;margin-right:auto}
.chat-area{flex:1;display:flex;flex-direction:column}
.chat-header{padding:12px 16px;border-bottom:0.5px solid var(--color-border-tertiary);display:flex;align-items:center;gap:10px}
.chat-messages{flex:1;overflow-y:auto;padding:1rem;display:flex;flex-direction:column;gap:10px}
.msg{max-width:70%;padding:9px 13px;border-radius:12px;font-size:13px;line-height:1.5}
.msg-in{background:var(--color-background-secondary);align-self:flex-end;border-radius:12px 12px 4px 12px}
.msg-out{background:#1D9E75;color:#fff;align-self:flex-start;border-radius:12px 12px 12px 4px}
.msg-time{font-size:10px;opacity:.7;margin-top:3px;text-align:left}
.chat-input-area{padding:12px;border-top:0.5px solid var(--color-border-tertiary);display:flex;gap:8px;align-items:flex-end}
.chat-input{flex:1;border:0.5px solid var(--color-border-secondary);border-radius:20px;padding:9px 14px;font-size:13px;background:var(--color-background-primary);color:var(--color-text-primary);font-family:var(--font-sans);resize:none;outline:none;max-height:100px}
.msg-exercise{background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:10px;padding:10px;margin-top:6px;font-size:12px}
</style>
@endpush
@section('content')
<div class="page-title">المحادثات</div>
<div class="chat-wrap">
<div class="chat-list">
@forelse($conversations as $conv)
@php $other = auth()->user()->role === 'trainer' ? $conv->member : $conv->trainer; @endphp
<div class="chat-list-item" onclick="loadChat({{ $conv->id }})">
    <div class="avatar" style="width:36px;height:36px;background:#E1F5EE;color:#0F6E56;font-size:14px">{{ substr($other->name ?? 'م', 0, 1) }}</div>
    <div style="flex:1;min-width:0">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div class="chat-list-name">{{ $other->name ?? 'مستخدم' }}</div>
            @if($conv->messages->where('is_read', false)->where('sender_id', '!=', auth()->id())->count())
            <span class="chat-unread">{{ $conv->messages->where('is_read', false)->where('sender_id', '!=', auth()->id())->count() }}</span>
            @endif
        </div>
        <div class="chat-list-last">{{ $conv->messages->last()->message ?? 'بداية المحادثة' }}</div>
    </div>
</div>
@empty
<div style="padding:2rem;text-align:center;color:var(--color-text-secondary);font-size:13px">لا توجد محادثات بعد</div>
@endforelse
</div>
<div class="chat-area" id="chat-area" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--color-text-secondary);font-size:13px">
    <i class="ti ti-message-circle" style="font-size:48px;margin-bottom:1rem;opacity:0.3"></i>
    اختر محادثة من القائمة
</div>
</div>
@endsection
@push('scripts')
<script>
let activeConvId = null;
function loadChat(id) {
    activeConvId = id;
    document.querySelectorAll('.chat-list-item').forEach(e => e.classList.remove('active'));
    event.currentTarget.classList.add('active');
    fetch(`/chat/${id}`).then(r => r.text()).then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        document.getElementById('chat-area').innerHTML = doc.querySelector('#chat-area-content').innerHTML;
        scrollChatDown();
    });
}
function scrollChatDown() {
    setTimeout(() => {
        const el = document.querySelector('.chat-messages');
        if(el) el.scrollTop = el.scrollHeight;
    }, 100);
}
function sendMsg() {
    const inp = document.getElementById('chat-text');
    if(!inp || !inp.value.trim()) return;
    fetch('{{ route('chat.send') }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({conversation_id: activeConvId, message: inp.value.trim()})
    }).then(r => r.json()).then(() => {
        inp.value = '';
        loadChat(activeConvId);
    });
}
</script>
@endpush
