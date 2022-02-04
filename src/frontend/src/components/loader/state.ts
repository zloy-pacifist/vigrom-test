import {EventEmitter, Listener} from 'events';

export default class LoaderState
{
    protected $emitter = new EventEmitter();
    protected listeners: { [key: string]: Listener } = {};
    protected _state = false;

    protected eventName = 'state_changed';

    subscribe(name: string, callback: Listener): this
    {
        if (this.listeners[name]) {
            this.unsubscribe(name);
        }

        this.$emitter.on(this.eventName, callback);
        this.listeners[name] = callback;
        return this;
    }

    unsubscribe(name: string): this
    {
        if (!this.listeners[name]) {
            return this;
        }

        this.$emitter.off(this.eventName, this.listeners[name]);

        return this;
    }

    get state(): boolean
    {
        return this._state;
    }

    set state(value: boolean)
    {
        this._state = value;
        this.$emitter.emit(this.eventName, value);
    }

    loadingStart(): void
    {
        this.state = true;
    }

    loadingStop(): void
    {
        this.state = false;
    }
}
