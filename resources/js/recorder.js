$(document).ready(function () {
    let log = console.log.bind(console),
        id = val => document.getElementById(val),
        ul = id('ul'),
        gUMbtn = id('gUMbtn'),
        start = id('start'),
        stop = id('stop'),
        stream,
        recorder,
        counter = 1,
        chunks,
        media;
    function makeLink() {
        let blob = new Blob(chunks, { type: media.type })
            , url = URL.createObjectURL(blob)
            , li = document.createElement('li')
            , mt = document.createElement(media.tag)
            , hf = document.createElement('a')
            ;
        mt.preload = "auto";
        mt.controls = true;
        mt.src = url;
        hf.href = url;
        hf.download = `${counter++}`;
        hf.innerHTML = `ClickToSave${hf.download}`;
        hf.style = "font-size:14px; font-weight: 600; margin-left: 10px";
        li.style = "margin-bottom: 10px";
        li.appendChild(mt);
        li.appendChild(hf);
        ul.appendChild(li);
    }
    gUMbtn.onclick = e => {
        let mediaOptions = {
                audio: {
                    tag: 'audio',
                    type: 'audio/ogg',
                    ext: '.ogg',
                    gUM: { audio: true }
                }
            };
        media = mediaOptions.audio;
        navigator.mediaDevices.getUserMedia(media.gUM).then(_stream => {
            stream = _stream;
            id('gUMArea').style.display = 'none';
            id('btns').style.display = 'inherit';
            start.removeAttribute('disabled');
            recorder = new MediaRecorder(stream);
            recorder.ondataavailable = e => {
                chunks.push(e.data);
                if (recorder.state == 'inactive') makeLink();
            };
            log('got media successfully');
        }).catch(log);
    }
    start.onclick = e => {
        id('img-block').style.display = 'inherit';
        start.disabled = true;
        stop.removeAttribute('disabled');
        chunks = [];
        recorder.start();
    }
    stop.onclick = e => {
        id('img-block').style.display = 'none';
        stop.disabled = true;
        recorder.stop();
        start.removeAttribute('disabled');
    }
});
