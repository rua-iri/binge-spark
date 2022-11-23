<div class="modal">
        <div class="modal-background"></div>
        <div class="modal-content has-background-white py-5 px-5">
            <!-- form for users to leave a review -->
            <div>
                <div>
                    <form action="process/processreview.php" method="POST">
                        <label class="has-text-centered">
                            <h2 class='title is-3'>Review <?=$filmT?></h2>
                        </label>
                        <label>
                            <?= $reviewError ?>
                        </label>
                        <div class="field mt-4 pt-4">
                            <label class="label is-size-4">Rating</label>

                            <div class="control has-text-centered">
                                <label class="label is-medium is-size-1">
                                    <span id="slider-rating">5&ensp;🙂</span>
                                </label>
                                <input class="rating-slider" value="5" type="range" min=1 max=10 name="rating" id="rating-range" onchange="revSlide(this.value)" onmousemove="revSlide(this.value)">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label is-size-4">Review</label>
                            <div class="control">
                                <textarea class="textarea" name="review" cols="30" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input class="button is-medium is-primary" type="submit" value="Post">
                            </div>
                        </div>

                        <input type="hidden" name="filmid" value="<?= $filmId ?>">
                    </form>
                </div>
            </div>

            
        </div>
    </div>