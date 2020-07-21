# Waveform Generator  

Convert the raw output from an audio silence detection [filter](https://ffmpeg.org/ffmpeg-filters.html#silencedetect)
into a useful JSON format for consumption by other APIs.

## Usage

The URL endpoint that produces the JSON fallow the standart Laravel Resource Controller [Actions](https://laravel.com/docs/master/controllers#resource-controllers).

In our case, if we run on localhost, it will be: *http://localhost:8000/api/conversation/{id}*
The **id** being the conversation identifier.

We store conversations *user* and *customer* channels in sotrage/app/conversation/{id}.
We have one conversation example data, so the url to test will be:

*http://localhost:8000/api/conversation/1*