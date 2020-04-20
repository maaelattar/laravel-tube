Vue.component("channel-uploads", {
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    data: () => ({
        selected: false,
        videos: [],
        progress: {},
        uploads: [],
        intervals: {}
    }),

    methods: {
        upload() {
            this.selected = true;
            this.videos = Array.from(this.$refs.videos.files);

            const uploaders = this.videos.map(video => {
                const form = new FormData();

                this.progress[video.name] = 0;

                form.append("video", video);
                form.append("title", video.name);

                return axios
                    .post(`/channels/${this.channel.id}/videos`, form, {
                        onUploadProgress: event => {
                            console.log(
                                "event loaded",
                                event.loaded,
                                "event total",
                                event.total
                            );
                            console.log("this.progress", this.progress);

                            this.progress[video.name] = Math.ceil(
                                (event.loaded / event.total) * 100
                            );
                            console.log("this.progress", this.progress);

                            this.$forceUpdate();
                        }
                    })
                    .then(({ data }) => {
                        console.log("channel-uploads data", data);
                        this.uploads = [...this.uploads, data];
                    });
            });

            axios.all(uploaders).then(() => {
                this.videos = this.uploads;

                this.videos.forEach(video => {
                    this.intervals[video.id] = setInterval(() => {
                        axios.get(`/videos/${video.id}`).then(({ data }) => {
                            if (data.thumbnail) {
                                clearInterval(this.intervals[video.id]);
                            }

                            this.videos = this.videos.map(v => {
                                if (v.id === data.id) {
                                    return data;
                                }

                                return v;
                            });
                        });
                    }, 2000);
                });
            });
        }
    }
});
