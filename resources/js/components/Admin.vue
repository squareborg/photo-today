<template>
    <div>
        <form method="post" action="/admin" enctype="multipart/form-data">
        <div class="form-group">
          <label for="file">Logo</label>
          <input class="form-control" type="file" id="logo" name="logo" accept="image/png, image/jpeg">
        </div>
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="bg" :value="bg" />
        <label for="bg">Background Colour</label>
        <background-colour-picker :initColour="settings.bg" @updated="bgUpdated" />
        <input type="submit" class="btn btn-primary" value="submit" />
        </form>
    </div>
</template>

<script>
export default {
    props: ['settings'],

    data() {
        return {
            bg: this.settings.bg,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },
    methods: {
        bgUpdated(updated) {
            this.bg = updated;
        }
    }
}
</script>
