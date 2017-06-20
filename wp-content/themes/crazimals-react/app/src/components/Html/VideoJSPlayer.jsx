import React, {Component} from 'react';
import videojs from 'video.js'

export default class VideoJSPlayer extends React.Component {

  componentDidMount() {
    // instantiate video.js
    this.player = videojs(this.videoNode, this.props, function onPlayerReady() {
      console.log('onPlayerReady', this)
    });
  }

  // destroy player on unmount
  componentWillUnmount() {
    if (this.player) {
      this.player.dispose()
    }
  }

  render() {
    return (
      <div data-vjs-player>
        <video className="video-js"></video>
      </div>
    )
  }
}

